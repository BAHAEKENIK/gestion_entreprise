<?php

namespace App\Http\Controllers;

use App\Models\Pointage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PointageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->hasRole('directeur')) {
            $selectedDate = $request->input('date', Carbon::today()->toDateString());
            $query = Pointage::with('employe')
                              ->whereDate('pointe_debut', $selectedDate)
                              ->orderBy('pointe_debut', 'desc');

            if ($request->filled('employe_id_filter')) {
                $query->where('employe_id', $request->employe_id_filter);
            }

            $pointages = $query->paginate(15);
            $employes = User::whereHas('roles', function ($q) {
                $q->where('name', 'employe');
            })->orderBy('name')->get();

            return view('pointages.index_directeur', compact('pointages', 'employes', 'selectedDate'));

        } elseif ($user->hasRole('employe')) {
            $pointagesAujourdhui = Pointage::where('employe_id', $user->id)
                ->whereDate('pointe_debut', Carbon::today())
                ->orderBy('pointe_debut', 'desc')
                ->get();

            $pointageActif = $pointagesAujourdhui->firstWhere('pointe_fin', null);

            $historiquePointages = Pointage::where('employe_id', $user->id)
                ->orderBy('pointe_debut', 'desc')
                ->paginate(10);

            return view('pointages.index_employe', compact('pointageActif', 'historiquePointages'));
        }

        return redirect()->route('dashboard')->with('error', 'Accès non autorisé à cette section.');
    }

    public function pointerArrivee(Request $request)
    {
        $user = Auth::user();
        $pointageExistant = Pointage::where('employe_id', $user->id)
            ->whereDate('pointe_debut', Carbon::today())
            ->whereNull('pointe_fin')
            ->first();

        if ($pointageExistant) {
            return redirect()->route('pointages.index')->with('error', 'Vous avez déjà pointé votre arrivée aujourd\'hui et n\'avez pas encore pointé votre départ.');
        }

        Pointage::create([
            'employe_id' => $user->id,
            'pointe_debut' => Carbon::now(),
            'description' => $request->input('description_arrivee'),
        ]);

        return redirect()->route('pointages.index')->with('success', 'Arrivée pointée avec succès.');
    }

    public function pointerDepart(Request $request)
    {
        $user = Auth::user();

        $pointageActif = Pointage::where('employe_id', $user->id)
            ->whereDate('pointe_debut', Carbon::today())
            ->whereNull('pointe_fin')
            ->orderBy('pointe_debut', 'desc')
            ->first();

        if (!$pointageActif) {
            return redirect()->route('pointages.index')->with('error', 'Aucun pointage d\'arrivée actif trouvé pour aujourd\'hui. Veuillez d\'abord pointer votre arrivée.');
        }

        $pointageActif->update([
            'pointe_fin' => Carbon::now(),
            'description' => $pointageActif->description . ($request->filled('description_depart') ? ' | Départ: ' . $request->input('description_depart') : ''),
        ]);

        return redirect()->route('pointages.index')->with('success', 'Départ pointé avec succès.');
    }
    public function historiqueEmploye(User $user, Request $request)
    {
        if (!$user->hasRole('employe')) {
            return redirect()->route('pointages.index')->with('error', 'Cet utilisateur n\'est pas un employé.');
        }

        $historiquePointages = Pointage::where('employe_id', $user->id)
            ->orderBy('pointe_debut', 'desc')
            ->paginate(15);

        return view('pointages.historique_employe_pour_directeur', compact('historiquePointages', 'user'));
    }
}
