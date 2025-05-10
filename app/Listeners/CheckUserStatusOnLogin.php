<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login; // Écouter l'événement Login
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException; // Pour déconnecter proprement

class CheckUserStatusOnLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event): void
    {
        if ($event->user && $event->user->statut !== 'actif') {
            $currentGuard = Auth::getDefaultDriver(); // Ou le guard spécifique si vous en utilisez plusieurs
            Auth::guard($currentGuard)->logout();

            // Préparer le message d'erreur
            $message = 'Votre compte est inactif ou en congé. Veuillez contacter l\'administrateur.';
            if ($event->user->statut === 'en_conge') {
                $message = 'Votre compte est actuellement en congé. Veuillez contacter l\'administrateur si vous pensez que c\'est une erreur.';
            }

            // Lancer une ValidationException pour afficher l'erreur sur le formulaire de login
            throw ValidationException::withMessages([
                'email' => [$message], // L'erreur sera associée au champ email
            ]);
        }
    }
}
