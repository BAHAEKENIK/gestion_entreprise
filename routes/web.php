<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PointageController; // Ajoutez ceci

/* ... autres routes ... */

Route::get('/', function () {
    // Redirige vers le dashboard si connecté, sinon vers login (géré par Breeze)
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});


Route::get('/dashboard', function () {
    // Redirection spécifique au rôle ici si nécessaire, ou gérez dans la vue dashboard
    if (auth()->user()->hasRole('directeur')) {
        return redirect()->route('pointages.index'); // Exemple de redirection pour directeur
    }
    return view('dashboard'); // Vue générique de Breeze
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Vos routes existantes pour Users et Roles (probablement pour le directeur)
    Route::middleware(['role:directeur'])->group(function () { // Protéger ces routes pour le directeur
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
    });


    // Routes pour les Pointages
    Route::get('pointages', [PointageController::class, 'index'])->name('pointages.index');
    Route::post('pointages/pointer-arrivee', [PointageController::class, 'pointerArrivee'])->name('pointages.arrivee');
    Route::patch('pointages/pointer-depart', [PointageController::class, 'pointerDepart'])->name('pointages.depart');

    // Route pour le directeur pour voir l'historique d'un employé spécifique
    Route::get('pointages/historique/{user}', [PointageController::class, 'historiqueEmploye'])
        ->middleware('role:directeur') // Seul le directeur peut voir l'historique des autres
        ->name('pointages.historique.employe');
});

require __DIR__.'/auth.php';
