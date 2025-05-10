<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TacheController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ============== POUR LE DIRECTEUR ==============

    /**
     * Affiche la liste des tâches (vue directeur) avec filtres.
     */
    public function indexDirecteur(Request $request)
    {
        $query = Tache::with(['employeAssignee', 'assignePar', 'documents'])
                      ->orderBy('created_at', 'desc');

        if ($request->filled('statut_filter')) {
            $query->where('statut', $request->statut_filter);
        }
        if ($request->filled('employe_id_filter')) {
            $query->where('employe_id', $request->employe_id_filter);
        }

        $taches = $query->paginate(15);
        $employes = User::whereHas('roles', fn($q) => $q->where('name', 'employe'))->orderBy('name')->get();
        $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee']; // Pour le filtre

        return view('taches.directeur.index', compact('taches', 'employes', 'statuts'));
    }

    /**
     * Affiche le formulaire de création de tâche.
     * Le paramètre $user est optionnel pour pré-remplir l'employé si on vient de la liste des employés.
     */
    public function create(User $user = null) 
    {
        $employes = User::whereHas('roles', fn($q) => $q->where('name', 'employe'))->orderBy('name')->get();
        $selectedEmployeId = $user ? $user->id : null;
        $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee'];
        return view('taches.directeur.create', compact('employes', 'selectedEmployeId', 'statuts'));
    }

    /**
     * Enregistre une nouvelle tâche.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employe_id' => 'required|exists:users,id',
            'statut' => 'required|in:a_faire,en_cours,terminee,en_revision,annulee',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
            'duree_estimee' => 'nullable|string|max:100',
            'document_tache' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip|max:10240', // 10MB max
        ]);

        $tache = Tache::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'employe_id' => $request->employe_id,
            'directeur_id' => Auth::id(),
            'statut' => $request->statut,
            'date_debut_prevue' => $request->date_debut_prevue,
            'date_fin_prevue' => $request->date_fin_prevue,
            'duree_estimee' => $request->duree_estimee,
            'date_assignation' => Carbon::now(),
        ]);

        if ($request->hasFile('document_tache')) {
            $file = $request->file('document_tache');
            $path = $file->store('documents_taches', 'public');

            Document::create([
                'nom_original' => $file->getClientOriginalName(),
                'chemin_stockage' => $path,
                'type_mime' => $file->getClientMimeType(),
                'expediteur_id' => Auth::id(),
                'recepteur_id' => $request->employe_id,
                'tache_id' => $tache->id,
            ]);
        }

        return redirect()->route('taches.directeur.index')->with('success', 'Tâche assignée avec succès.');
    }


    /**
     * Affiche le formulaire d'édition d'une tâche pour le directeur.
     */
    public function editDirecteur(Tache $tache)
    {
        $employes = User::whereHas('roles', fn($q) => $q->where('name', 'employe'))->orderBy('name')->get();
        $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee'];
        return view('taches.directeur.edit', compact('tache', 'employes', 'statuts'));
    }

    /**
     * Met à jour une tâche (par le directeur).
     */
    public function updateDirecteur(Request $request, Tache $tache)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employe_id' => 'required|exists:users,id',
            'statut' => 'required|in:a_faire,en_cours,terminee,en_revision,annulee',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
            'duree_estimee' => 'nullable|string|max:100',
        ]);

        $tache->update($request->only([
            'titre', 'description', 'employe_id', 'statut',
            'date_debut_prevue', 'date_fin_prevue', 'duree_estimee'
        ]));

        if ($request->statut === 'terminee' && is_null($tache->date_completion)) {
            $tache->date_completion = Carbon::now();
            $tache->save();
        } elseif ($request->statut !== 'terminee') {
            $tache->date_completion = null;
            $tache->save();
        }


        return redirect()->route('taches.directeur.index')->with('success', 'Tâche mise à jour avec succès.');
    }


    // ============== POUR L'EMPLOYÉ ==============

    /**
     * Affiche la liste des tâches pour l'employé connecté.
     */
    public function indexEmploye(Request $request)
    {
        $user = Auth::user();
        $query = Tache::with('assignePar', 'documents')
                      ->where('employe_id', $user->id)
                      ->orderBy('created_at', 'desc');

        if ($request->filled('statut_filter_employe')) {
            $query->where('statut', $request->statut_filter_employe);
        }

        $taches = $query->paginate(15);
        $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee']; // Pour le filtre

        return view('taches.employe.index', compact('taches', 'statuts'));
    }

    /**
     * Affiche le formulaire pour que l'employé "réalise" ou mette à jour une tâche.
     */
    public function showRealisationForm(Tache $tache)
    {
        if ($tache->employe_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette tâche.');
        }
        $statutsPossiblesEmploye = ['en_cours', 'en_revision', 'terminee'];
        return view('taches.employe.realiser', compact('tache', 'statutsPossiblesEmploye'));
    }

    /**
     * Soumet la réalisation/mise à jour d'une tâche par l'employé.
     */
    public function submitRealisation(Request $request, Tache $tache)
    {
        if ($tache->employe_id !== Auth::id()) {
            abort(403, 'Accès non autorisé à cette tâche.');
        }

        $request->validate([
            'statut' => 'required|in:en_cours,en_revision,terminee',
            'description_employe' => 'nullable|string',
            'document_realisation' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip|max:10240',
        ]);

        $descriptionOriginale = $tache->description;
        $nouvelleDescription = $descriptionOriginale;
        if ($request->filled('description_employe')) {
            $nouvelleDescription .= "\n\n--- Commentaire Employé (".Carbon::now()->format('d/m/Y H:i').") ---\n" . $request->description_employe;
        }

        $tache->update([
            'statut' => $request->statut,
            'description' => $nouvelleDescription,
            'date_completion' => ($request->statut === 'terminee' && is_null($tache->date_completion)) ? Carbon::now() : $tache->date_completion,
        ]);

        if ($request->statut !== 'terminee' && !is_null($tache->date_completion)) {
            $tache->date_completion = null;
            $tache->save();
        }


        if ($request->hasFile('document_realisation')) {
            $file = $request->file('document_realisation');
            $path = $file->store('documents_taches_realisation', 'public');
            Document::create([
                'nom_original' => $file->getClientOriginalName(),
                'chemin_stockage' => $path,
                'type_mime' => $file->getClientMimeType(),
                'expediteur_id' => Auth::id(),
                'recepteur_id' => $tache->directeur_id,
                'tache_id' => $tache->id,
                'description' => 'Document de réalisation soumis par l\'employé.',
            ]);
        }

        return redirect()->route('taches.employe.index')->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Affiche les détails d'une tâche.
     */
    public function show(Tache $tache)
    {
        $user = Auth::user();
        if (!($user->hasRole('directeur') || $tache->employe_id === $user->id)) {
            abort(403, 'Accès non autorisé à cette tâche.');
        }
        $tache->load('employeAssignee', 'assignePar', 'documents');
        return view('taches.show', compact('tache'));
    }

    /**
     * Supprime une tâche (uniquement par le directeur).
     */
    public function destroy(Tache $tache)
    {
        foreach ($tache->documents as $document) {
            Storage::disk('public')->delete($document->chemin_stockage);
            $document->delete();
        }
        $tache->delete();
        return redirect()->route('taches.directeur.index')->with('success', 'Tâche supprimée avec succès.');
    }

    /**
     * Télécharge un document associé à une tâche.
     */
    public function telechargerDocumentTache(Tache $tache, Document $document)
    {
        $user = Auth::user();
        if (!($user->hasRole('directeur') || $tache->employe_id === $user->id)) {
            abort(403, 'Accès non autorisé.');
        }
        if ($document->tache_id !== $tache->id) {
            abort(404, 'Document non trouvé pour cette tâche.');
        }

        $path = storage_path('app/public/' . $document->chemin_stockage);

        if (!Storage::disk('public')->exists($document->chemin_stockage)) {
            abort(404, 'Fichier non trouvé.');
        }
        return response()->download($path, $document->nom_original);
    }
}
