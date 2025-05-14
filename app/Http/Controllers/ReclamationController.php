<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReclamationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:reclamation-list-employe|reclamation-list-directeur', ['only' => ['index']]);
        $this->middleware('permission:reclamation-create-employe', ['only' => ['create', 'store']]);
        $this->middleware('permission:reclamation-view', ['only' => ['show']]);
        $this->middleware('permission:reclamation-traiter-directeur', ['only' => ['edit', 'update']]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $statuts = ['soumise', 'en_cours_traitement', 'resolue', 'rejetee'];

        if ($user->hasRole('directeur')) {
            $query = Reclamation::with(['auteur', 'destinataire'])->latest();

            if ($request->filled('statut_filter')) {
                $query->where('statut', $request->statut_filter);
            }
            if ($request->filled('employe_filter')) {
                $query->where('employe_id', $request->employe_filter);
            }
            $query->where('directeur_id', $user->id);

            $reclamations = $query->paginate(10);
            $employes = User::role('employe')->orderBy('name')->get();

            return view('reclamations.directeur.index', compact('reclamations', 'statuts', 'employes'));

        } elseif ($user->hasRole('employe')) {
            $query = Reclamation::with('destinataire')
                                ->where('employe_id', $user->id)
                                ->latest();

            if ($request->filled('statut_filter')) {
                $query->where('statut', $request->statut_filter);
            }
            $reclamations = $query->paginate(10);
            return view('reclamations.employe.index', compact('reclamations', 'statuts'));
        }
        return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
    }

    public function create()
    {
        $directeurs = User::role('directeur')->get();
        if ($directeurs->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun directeur n\'est disponible pour recevoir des réclamations.');
        }
        return view('reclamations.employe.create', compact('directeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'directeur_id' => 'required|exists:users,id',
            'sujet' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        $directeur = User::find($request->directeur_id);
        if (!$directeur || !$directeur->hasRole('directeur')) {
            return redirect()->back()->with('error', 'Le destinataire sélectionné n\'est pas un directeur valide.')->withInput();
        }

        Reclamation::create([
            'employe_id' => Auth::id(),
            'directeur_id' => $request->directeur_id,
            'sujet' => $request->sujet,
            'description' => $request->description,
            'statut' => 'soumise',
        ]);

        return redirect()->route('reclamations.index')->with('success', 'Réclamation soumise avec succès.');
    }

    public function show(Reclamation $reclamation)
    {
        $user = Auth::user();
        if (!($user->hasRole('directeur') && $reclamation->directeur_id == $user->id) &&
            !($user->hasRole('employe') && $reclamation->employe_id == $user->id)) {
            abort(403, 'Accès non autorisé à cette réclamation.');
        }
        $reclamation->load(['auteur', 'destinataire']);
        return view('reclamations.show', compact('reclamation'));
    }

    public function edit(Reclamation $reclamation)
    {
        if ($reclamation->directeur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à traiter cette réclamation.');
        }
        $statuts = ['en_cours_traitement', 'resolue', 'rejetee'];
        return view('reclamations.directeur.edit', compact('reclamation', 'statuts'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        if ($reclamation->directeur_id !== Auth::id()) {
            abort(403, 'Vous n\'êtes pas autorisé à traiter cette réclamation.');
        }

        $request->validate([
            'reponse' => 'required_if:statut,resolue,rejetee|nullable|string',
            'statut' => 'required|in:en_cours_traitement,resolue,rejetee',
        ]);

        $reclamation->update([
            'reponse' => $request->reponse,
            'statut' => $request->statut,
            'date_reponse' => Carbon::now(),
        ]);

        return redirect()->route('reclamations.index')->with('success', 'Réclamation traitée avec succès.');
    }
}
