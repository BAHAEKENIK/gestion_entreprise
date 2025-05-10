<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Pour la validation du thème

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse // ProfileUpdateRequest gère name et email
    {
        $user = $request->user();
        $validatedData = $request->validated(); // Contient name et email validés

        // Gérer la mise à jour du téléphone si envoyé
        if ($request->has('telephone')) {
            $request->validate(['telephone' => 'nullable|string|max:20']);
            $validatedData['telephone'] = $request->telephone;
        }

        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's theme preference.
     * Nouvelle méthode pour gérer spécifiquement le thème.
     */
    public function updateTheme(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => ['required', Rule::in(['light', 'dark'])],
        ]);

        $user = $request->user();
        $user->theme = $request->theme;
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'theme-updated');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
