<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\Auth\ChangePasswordController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.update.theme');

    Route::middleware(['role:directeur'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);

        Route::get('/users-export', [UserController::class, 'exportUsers'])->name('users.export');
        Route::get('/users-import', [UserController::class, 'importUsersForm'])->name('users.import.form');
        Route::post('/users-import', [UserController::class, 'importUsers'])->name('users.import');

        Route::get('taches/creer/{user?}', [TacheController::class, 'create'])->name('taches.create');
        Route::post('taches', [TacheController::class, 'store'])->name('taches.store');
        Route::get('taches/directeur', [TacheController::class, 'indexDirecteur'])->name('taches.directeur.index');
        Route::get('taches/{tache}/edit-directeur', [TacheController::class, 'editDirecteur'])->name('taches.directeur.edit');
        Route::put('taches/{tache}/update-directeur', [TacheController::class, 'updateDirecteur'])->name('taches.directeur.update');
        Route::delete('taches/{tache}', [TacheController::class, 'destroy'])->name('taches.destroy');

        Route::get('pointages/historique/{user}', [PointageController::class, 'historiqueEmploye'])->name('pointages.historique.employe');

        Route::get('reclamations/{reclamation}/traiter', [ReclamationController::class, 'edit'])->name('reclamations.edit');
        Route::patch('reclamations/{reclamation}', [ReclamationController::class, 'update'])->name('reclamations.update');
    });

    Route::get('pointages', [PointageController::class, 'index'])->name('pointages.index');
    Route::post('pointages/pointer-arrivee', [PointageController::class, 'pointerArrivee'])->name('pointages.arrivee')->middleware('role:employe');
    Route::patch('pointages/pointer-depart', [PointageController::class, 'pointerDepart'])->name('pointages.depart')->middleware('role:employe');

    Route::middleware(['role:employe'])->group(function () {
        Route::get('taches/employe', [TacheController::class, 'indexEmploye'])->name('taches.employe.index');
        Route::get('taches/{tache}/realiser', [TacheController::class, 'showRealisationForm'])->name('taches.realiser.form');
        Route::patch('taches/{tache}/realiser', [TacheController::class, 'submitRealisation'])->name('taches.realiser.submit');

        Route::get('reclamations/creer', [ReclamationController::class, 'create'])->name('reclamations.create');
        Route::post('reclamations', [ReclamationController::class, 'store'])->name('reclamations.store');
    });

    Route::get('reclamations', [ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('reclamations/{reclamation}', [ReclamationController::class, 'show'])->name('reclamations.show');

    Route::get('taches/{tache}', [TacheController::class, 'show'])->name('taches.show');
    Route::get('taches/{tache}/document/{document}/telecharger', [TacheController::class, 'telechargerDocumentTache'])->name('taches.document.telecharger');

    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change.submit');

});

require __DIR__.'/auth.php';
