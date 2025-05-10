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
        // Appliquer la politique d'autorisation pour les tâches
        $this->authorizeResource(Tache::class, 'tache');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee']; // Pour les filtres

        if ($user->hasRole('directeur')) {
            $query = Tache::with(['employeAssignee', 'assignePar', 'documents'])->latest('date_assignation');

            if ($request->filled('statut_filter')) {
                $query->where('statut', $request->statut_filter);
            }
            if ($request->filled('employe_id_filter')) {
                $query->where('employe_id', $request->employe_id_filter);
            }

            $taches = $query->paginate(10);
            $employes = User::whereHas('roles', fn($q) => $q->where('name', 'employe'))->orderBy('name')->get();
            return view('taches.index_directeur', compact('taches', 'employes', 'statuts'));

        } elseif ($user->hasRole('employe')) {
            $query = Tache::where('employe_id', $user->id)
                ->with(['assignePar', 'documents'])
                ->latest('date_assignation');

            if ($request->filled('statut_filter')) {
                $query->where('statut', $request->statut_filter);
            }
            $taches = $query->paginate(10);
            return view('taches.index_employe', compact('taches', 'statuts'));
        }
        return redirect()->route('dashboard')->with('error', 'Vue non définie pour votre rôle.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() // Seul le directeur peut créer
    {
        $employes = User::whereHas('roles', fn ($q) => $q->where('name', 'employe'))->orderBy('name')->get();
        $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee'];
        return view('taches.create', compact('employes', 'statuts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Seul le directeur peut stocker
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'employe_id' => 'required|exists:users,id',
            'statut' => 'required|in:a_faire,en_cours,terminee,en_revision,annulee',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
            'duree_estimee' => 'nullable|string|max:100',
            'document_tache' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120', // Max 5MB
        ]);

        $tache = Tache::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'employe_id' => $request->employe_id,
            'directeur_id' => Auth::id(),
            'statut' => $request->statut,
            'date_assignation' => Carbon::now(),
            'date_debut_prevue' => $request->date_debut_prevue,
            'date_fin_prevue' => $request->date_fin_prevue,
            'duree_estimee' => $request->duree_estimee,
        ]);

        if ($request->hasFile('document_tache')) {
            $file = $request->file('document_tache');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('tache_documents/' . $tache->id, $filename, 'public'); // Stocke dans storage/app/public/tache_documents/ID_TACHE/

            Document::create([
                'nom_original' => $file->getClientOriginalName(),
                'chemin_stockage' => $path,
                'type_mime' => $file->getMimeType(),
                'expediteur_id' => Auth::id(),       // Directeur
                'recepteur_id' => $request->employe_id, // Employé
                'tache_id' => $tache->id,
            ]);
        }

        return redirect()->route('taches.index')->with('success', 'Tâche créée et assignée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tache $tache)
    {
        $tache->load(['employeAssignee', 'assignePar', 'documents.expediteur']); // Charger l'expéditeur du document
        return view('taches.show', compact('tache'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tache $tache)
    {
        if (Auth::user()->hasRole('directeur')) {
            $employes = User::whereHas('roles', fn ($q) => $q->where('name', 'employe'))->orderBy('name')->get();
            $statuts = ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee'];
            return view('taches.edit_directeur', compact('tache', 'employes', 'statuts'));
        } elseif (Auth::user()->hasRole('employe') && $tache->employe_id === Auth::id()) {
             // L'employé ne peut que changer le statut et ajouter des documents de complétion
            $statutsPermisEmploye = ['en_cours', 'terminee', 'en_revision']; // Exemple de statuts que l'employé peut définir
            if ($tache->statut === 'a_faire') {
                $statutsPermisEmploye = ['en_cours', 'a_faire'];
            } elseif ($tache->statut === 'en_cours') {
                 $statutsPermisEmploye = ['en_cours', 'terminee', 'en_revision'];
            } elseif ($tache->statut === 'en_revision') {
                $statutsPermisEmploye = ['en_cours', 'terminee', 'en_revision']; // Le directeur peut le remettre en_cours
            } elseif ($tache->statut === 'terminee' || $tache->statut === 'annulee') {
                return redirect()->route('taches.show', $tache)->with('info', 'Cette tâche ne peut plus être modifiée par vous.');
            }
            return view('taches.edit_employe', compact('tache', 'statutsPermisEmploye'));
        }
        return redirect()->route('taches.index')->with('error', 'Action non autorisée.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tache $tache)
    {
        if (Auth::user()->hasRole('directeur')) {
            $request->validate([
                'titre' => 'required|string|max:255',
                'description' => 'nullable|string',
                'employe_id' => 'required|exists:users,id',
                'statut' => 'required|in:a_faire,en_cours,terminee,en_revision,annulee',
                'date_debut_prevue' => 'nullable|date',
                'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
                'duree_estimee' => 'nullable|string|max:100',
                // Pas de re-upload de document principal ici, gérer séparément ou via une section "documents"
            ]);
            $dataToUpdate = $request->only(['titre', 'description', 'employe_id', 'statut', 'date_debut_prevue', 'date_fin_prevue', 'duree_estimee']);
            if ($request->statut === 'terminee' && is_null($tache->date_completion)) {
                $dataToUpdate['date_completion'] = Carbon::now();
            } elseif ($request->statut !== 'terminee') {
                $dataToUpdate['date_completion'] = null;
            }
            $tache->update($dataToUpdate);
            return redirect()->route('taches.show', $tache)->with('success', 'Tâche mise à jour avec succès.');

        } elseif (Auth::user()->hasRole('employe') && $tache->employe_id === Auth::id()) {
            $request->validate([
                'statut_employe' => 'required|in:a_faire,en_cours,terminee,en_revision',
                'document_completion' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
            ]);

            if ($tache->statut === 'terminee' || $tache->statut === 'annulee') {
                 return redirect()->route('taches.show', $tache)->with('info', 'Cette tâche ne peut plus être modifiée.');
            }

            $dataToUpdate = ['statut' => $request->statut_employe];
            if ($request->statut_employe === 'terminee') {
                $dataToUpdate['date_completion'] = Carbon::now();
            }

            $tache->update($dataToUpdate);

            if ($request->hasFile('document_completion')) {
                $file = $request->file('document_completion');
                $filename = time() . '_completion_' . $file->getClientOriginalName();
                $path = $file->storeAs('tache_documents/' . $tache->id, $filename, 'public');

                Document::create([
                    'nom_original' => $file->getClientOriginalName(),
                    'chemin_stockage' => $path,
                    'type_mime' => $file->getMimeType(),
                    'expediteur_id' => Auth::id(),      // Employé
                    'recepteur_id' => $tache->directeur_id, // Directeur
                    'tache_id' => $tache->id,
                    'description' => 'Document de complétion de tâche',
                ]);
            }
            return redirect()->route('taches.show', $tache)->with('success', 'Statut de la tâche mis à jour.');
        }
        return redirect()->route('taches.index')->with('error', 'Action non autorisée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tache $tache) // Seul le directeur peut supprimer
    {
        // Optionnel: Supprimer les documents associés du stockage
        foreach ($tache->documents as $document) {
            Storage::disk('public')->delete($document->chemin_stockage);
            $document->delete(); // Supprime l'entrée de la BDD
        }
        $tache->delete();
        return redirect()->route('taches.index')->with('success', 'Tâche supprimée avec succès.');
    }

    // Méthode spécifique pour que l'employé mette à jour le statut (ex: "en_cours")
    public function updateEmployeStatus(Request $request, Tache $tache)
    {
        $this->authorize('update', $tache); // Vérifie si l'utilisateur actuel peut mettre à jour cette tâche

        $request->validate([
            'statut' => 'required|in:a_faire,en_cours,terminee,en_revision', // Statuts permis pour l'employé
        ]);

        if ($tache->employe_id !== Auth::id()) {
            return back()->with('error', 'Action non autorisée.');
        }
        if ($tache->statut === 'terminee' || $tache->statut === 'annulee') {
             return back()->with('info', 'Cette tâche ne peut plus être modifiée.');
        }

        $dataToUpdate = ['statut' => $request->statut];
        if ($request->statut === 'terminee' && is_null($tache->date_completion)) {
            $dataToUpdate['date_completion'] = Carbon::now();
        } elseif ($request->statut !== 'terminee') {
            $dataToUpdate['date_completion'] = null;
        }

        $tache->update($dataToUpdate);
        return redirect()->route('taches.show', $tache)->with('success', 'Statut de la tâche mis à jour.');
    }

    // Pour que l'employé télécharge un document APRÈS la création de la tâche
    public function uploadDocumentEmploye(Request $request, Tache $tache)
    {
        $this->authorize('update', $tache);

        $request->validate([
            'document_additionnel' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:5120',
            'description_document' => 'nullable|string|max:255',
        ]);

        if ($tache->employe_id !== Auth::id()) {
            return back()->with('error', 'Action non autorisée.');
        }

        $file = $request->file('document_additionnel');
        $filename = time() . '_employe_' . $file->getClientOriginalName();
        $path = $file->storeAs('tache_documents/' . $tache->id, $filename, 'public');

        Document::create([
            'nom_original' => $file->getClientOriginalName(),
            'chemin_stockage' => $path,
            'type_mime' => $file->getMimeType(),
            'expediteur_id' => Auth::id(),
            'recepteur_id' => $tache->directeur_id,
            'tache_id' => $tache->id,
            'description' => $request->description_document ?? 'Document additionnel par l\'employé',
        ]);

        return redirect()->route('taches.show', $tache)->with('success', 'Document téléversé avec succès.');
    }

    public function destroyDocument(Document $document)
    {
        $tache = $document->tache; // Récupérer la tâche associée
        // Autorisation: Seul l'expéditeur du document ou le directeur assigné à la tâche peut supprimer
        if (!Auth::user()->hasRole('directeur') && $document->expediteur_id !== Auth::id()) {
            if (!($tache && $tache->directeur_id === Auth::id())) {
                 return back()->with('error', 'Action non autorisée pour supprimer ce document.');
            }
        }

        Storage::disk('public')->delete($document->chemin_stockage);
        $document->delete();

        return redirect()->route('taches.show', $tache)->with('success', 'Document supprimé avec succès.');
    }
}
