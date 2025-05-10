<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate(); // Valide les identifiants

        // Après que les identifiants sont validés, mais avant de régénérer la session
        $user = Auth::getProvider()->retrieveByCredentials($request->only('email')); // Récupère l'utilisateur par email

        if ($user) { // Vérifie si l'utilisateur existe
            if ($user->statut !== 'actif') {
                // Déconnecter l'utilisateur car il a été authentifié par $request->authenticate()
                // mais son statut n'est pas actif.
                Auth::logout(); // Ou $request->session()->invalidate(); $request->session()->regenerateToken();
                                // Auth::logout() est plus complet.

                $message = 'Votre compte est inactif ou en congé. Veuillez contacter l\'administrateur.';
                if ($user->statut === 'en_conge') {
                    $message = 'Votre compte est actuellement en congé. Veuillez contacter l\'administrateur si vous pensez que c\'est une erreur.';
                }

                return redirect()->route('login')
                                 ->withErrors(['email' => $message])
                                 ->onlyInput('email');
            }
        } else {
            // Cela ne devrait pas arriver si $request->authenticate() a réussi,
            // mais c'est une sécurité.
             return redirect()->route('login')
                                 ->withErrors(['email' => __('auth.failed')])
                                 ->onlyInput('email');
        }


        // Si l'utilisateur est actif, continuer la régénération de la session et la redirection.
        $request->session()->regenerate();

        // Redirection après le login (vous avez peut-être une logique personnalisée ici)
        $authUser = Auth::user(); // Maintenant, on peut utiliser Auth::user()
        if ($authUser->must_change_password) {
            return redirect()->route('password.change.form') // Correct
                ->with('status', 'Veuillez changer votre mot de passe initial.');
        }

        if ($authUser->hasRole('directeur')) {
            return redirect()->intended(route('taches.directeur.index')); // Ou votre route de dashboard directeur
        } elseif ($authUser->hasRole('employe')) {
            return redirect()->intended(route('taches.employe.index')); // Ou votre route de dashboard employé
        }

        return redirect()->intended(RouteServiceProvider::HOME); // Fallback
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
