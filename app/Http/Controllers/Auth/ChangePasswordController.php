<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password; // Pour des règles de mot de passe plus robustes

class ChangePasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Seuls les utilisateurs connectés peuvent accéder
    }

    /**
     * Affiche le formulaire de changement de mot de passe.
     */
    public function showChangePasswordForm()
    {
        // Vérifier si l'utilisateur doit vraiment changer son mot de passe
        // Cela ajoute une sécurité si l'utilisateur essaie d'accéder directement à l'URL
        if (!Auth::user()->must_change_password) {
            return redirect()->route('dashboard'); // Ou la page d'accueil appropriée
        }
        return view('auth.passwords.change-initial'); // Nom de vue suggéré
    }

    /**
     * Traite la soumission du formulaire de changement de mot de passe.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        // S'assurer qu'il est bien censé changer son mot de passe via ce flux
        if (!$user->must_change_password) {
            return redirect()->route('dashboard');
        }

        $request->validate([
            // Si c'est le premier changement, on n'a pas besoin de 'current_password'
            // car le mot de passe actuel est celui par défaut.
            // 'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults(), 'different:current_default_password'],
            // 'current_default_password' sera une astuce pour vérifier contre le mot de passe par défaut si nécessaire,
            // mais pour l'instant, la logique est que 'must_change_password' est le gardien.
        ], [
            'password.different' => 'Le nouveau mot de passe doit être différent du mot de passe par défaut.'
        ]);

        $user->password = Hash::make($request->password);
        $user->must_change_password = false; // Très important : mettre à jour cet indicateur
        $user->save();

        // Rediriger vers le dashboard approprié après le changement
        if ($user->hasRole('directeur')) {
            return redirect()->route('taches.directeur.index')->with('status', 'Mot de passe changé avec succès !');
        } elseif ($user->hasRole('employe')) {
            return redirect()->route('taches.employe.index')->with('status', 'Mot de passe changé avec succès !');
        }

        return redirect()->route('dashboard')->with('status', 'Mot de passe changé avec succès !');
    }
}
