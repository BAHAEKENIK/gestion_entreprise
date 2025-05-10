<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TacheController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () { // OUVERTURE 1

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes pour le directeur
    Route::middleware(['role:directeur'])->group(function () { // OUVERTURE 2
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);

        // Routes Tâches pour Directeur
        Route::get('taches/creer/{user?}', [TacheController::class, 'create'])->name('taches.create');
        Route::post('taches', [TacheController::class, 'store'])->name('taches.store');
        Route::get('taches/directeur', [TacheController::class, 'indexDirecteur'])->name('taches.directeur.index');
        Route::get('taches/{tache}/edit-directeur', [TacheController::class, 'editDirecteur'])->name('taches.directeur.edit');
        Route::put('taches/{tache}/update-directeur', [TacheController::class, 'updateDirecteur'])->name('taches.directeur.update');
        Route::delete('taches/{tache}', [TacheController::class, 'destroy'])->name('taches.destroy'); // Déplacé ici pour la cohérence

        // Route Pointages pour Directeur (Historique spécifique)
        Route::get('pointages/historique/{user}', [PointageController::class, 'historiqueEmploye'])
            ->name('pointages.historique.employe');

    });
    Route::get('pointages', [PointageController::class, 'index'])->name('pointages.index');
    Route::post('pointages/pointer-arrivee', [PointageController::class, 'pointerArrivee'])->name('pointages.arrivee')->middleware('role:employe');
    Route::patch('pointages/pointer-depart', [PointageController::class, 'pointerDepart'])->name('pointages.depart')->middleware('role:employe');
    Route::patch('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.update.theme');


    // Routes Tâches pour l'Employé
    Route::middleware(['role:employe'])->group(function () { // OUVERTURE 3
        Route::get('taches/employe', [TacheController::class, 'indexEmploye'])->name('taches.employe.index');
        Route::get('taches/{tache}/realiser', [TacheController::class, 'showRealisationForm'])->name('taches.realiser.form');
        Route::patch('taches/{tache}/realiser', [TacheController::class, 'submitRealisation'])->name('taches.realiser.submit');
    }); // FERMETURE 3 (pour role:employe)


    // Route commune pour voir une tâche (accessible par le directeur et l'employé assigné)
    Route::get('taches/{tache}', [TacheController::class, 'show'])->name('taches.show');

    // Route pour télécharger un document attaché à une tâche
    Route::get('taches/{tache}/document/{document}/telecharger', [TacheController::class, 'telechargerDocumentTache'])->name('taches.document.telecharger');

    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('password.change.form');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('password.change.submit');

}); 

require __DIR__.'/auth.php';
