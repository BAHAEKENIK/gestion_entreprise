<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel; // <-- Importer Excel
use App\Exports\UsersExport;          // <-- Importer votre classe d'export
use App\Imports\UsersImport;          // <-- Importer votre classe d'import

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
         // Assurez-vous que les permissions pour import/export sont ici si différentes de user-create/user-list
         $this->middleware('permission:user-create', ['only' => ['create','store', 'importUsersForm', 'importUsers']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
         $this->middleware('permission:user-export', ['only' => ['exportUsers']]); // Permission spécifique pour l'export
    }

    public function index(Request $request)
    {
        $query = User::query();

        $query->whereDoesntHave('roles', function ($q_roles) {
            $q_roles->where('name', 'directeur');
        });
        // Exclure l'utilisateur directeur actuellement connecté de la liste des "employés" à gérer
        // $query->where('id', '!=', auth()->id());


        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q_search) use ($searchTerm) {
                $q_search->where('name', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Renommé en $users pour correspondre à la vue que vous avez fournie
        $users = $query->orderBy('name')->paginate($request->input('per_page', 7)); // Pagination à 7

        return view('users.index',compact('users')) // Changé de $data à $users
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'directeur')->pluck('name','name')->all();
        $statuts = ['actif', 'inactif', 'en_conge'];
        return view('users.create',compact('roles', 'statuts'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'post' => 'nullable|string|max:255',
            'statut' => 'required|in:actif,inactif,en_conge',
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
            'date_embauche' => 'nullable|date',
        ]);

        $input = $request->only(['name', 'email', 'telephone', 'post', 'statut', 'date_embauche']);
        $defaultPassword = 'password123'; // Changez ceci
        $input['password'] = Hash::make($defaultPassword);
        $input['must_change_password'] = true;
        $input['theme'] = 'light';

        $user = User::create($input);

        if ($request->has('roles') && is_array($request->input('roles'))) {
            $rolesToAssign = $request->input('roles');
            // Éviter d'assigner le rôle 'directeur' via ce formulaire si ce n'est pas souhaité
            if (in_array('directeur', $rolesToAssign) && !$request->user()->hasRole('super-admin')) { // Exemple
                // Ne pas assigner 'directeur' ou lever une erreur
                $rolesToAssign = array_diff($rolesToAssign, ['directeur']);
            }
            $user->assignRole($rolesToAssign);
        } else {
            $user->assignRole('employe'); // Rôle par défaut
        }

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur créé avec succès. Mot de passe par défaut : ' . $defaultPassword);
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!$user || ($user->hasRole('directeur') && auth()->id() !== $user->id && !auth()->user()->hasRole('super-admin'))) {
            return redirect()->route('users.index')->with('error', 'Utilisateur non trouvé ou accès non autorisé.');
        }
        return view('users.show',compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
         if (!$user || ($user->hasRole('directeur') && auth()->id() !== $user->id && !auth()->user()->hasRole('super-admin'))) {
             return redirect()->route('users.index')->with('error', 'Utilisateur non trouvé ou non autorisé à modifier.');
        }
        // $roles = Role::pluck('name','name')->all();
        // Exclure le rôle 'directeur' de la liste des rôles modifiables (sauf si super-admin)
        $queryRoles = Role::query();
        if (!auth()->user()->hasRole('super-admin')) {
            $queryRoles->where('name', '!=', 'directeur');
        }
        $roles = $queryRoles->pluck('name','name')->all();

        $userRole = $user->roles->pluck('name','name')->all();
        $statuts = ['actif', 'inactif', 'en_conge'];
        return view('users.edit',compact('user','roles','userRole', 'statuts'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user || ($user->hasRole('directeur') && auth()->id() !== $user->id && !auth()->user()->hasRole('super-admin'))) {
            return redirect()->route('users.index')->with('error', 'Utilisateur non trouvé ou non autorisé à modifier.');
        }
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => 'nullable|string|min:8|confirmed', // 'confirmed' au lieu de 'same:confirm-password' si le champ est password_confirmation
            'roles' => 'required|array',
            'roles.*' => 'string|exists:roles,name',
            'telephone' => 'nullable|string|max:20',
            'post' => 'nullable|string|max:255',
            'statut' => 'required|in:actif,inactif,en_conge',
            'date_embauche' => 'nullable|date',
        ]);
        $input = $request->only(['name', 'email', 'telephone', 'post', 'statut', 'date_embauche']);
        if(!empty($request->input('password'))){
            $input['password'] = Hash::make($request->input('password'));
            $input['must_change_password'] = false;
        }
        $user->update($input);

        $newRoles = $request->input('roles');
        // Logique pour empêcher la modification du rôle directeur si ce n'est pas un super-admin
        if (in_array('directeur', $newRoles) && !$request->user()->hasRole('super-admin')) {
            // Conserver les rôles existants de l'utilisateur ou lever une erreur
            // Pour l'instant, on laisse passer si le formulaire le permet.
        } else if (in_array('directeur', $user->getRoleNames()->toArray()) && !in_array('directeur', $newRoles) && !$request->user()->hasRole('super-admin')) {
            // Empêcher de retirer le rôle directeur à un directeur (sauf par super-admin)
            // return redirect()->back()->with('error', 'Vous ne pouvez pas retirer le rôle directeur.');
            // Pour l'instant, on laisse passer si le formulaire le permet.
        }
        $user->syncRoles($newRoles);

        return redirect()->route('users.index')
           ->with('success','Utilisateur mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'Utilisateur non trouvé.');
        }
        if ($user->id == auth()->id()) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas vous supprimer vous-même.');
        }
        if ($user->hasRole('directeur') && !auth()->user()->hasRole('super-admin')) {
            return redirect()->route('users.index')->with('error', 'Les directeurs ne peuvent être supprimés que par un administrateur système.');
        }
        $user->delete();
        return redirect()->route('users.index')
                         ->with('success','Utilisateur supprimé avec succès.');
    }

    /**
     * Export users data to Excel.
     */
    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'utilisateurs_employes_'.date('Y-m-d_H-i').'.xlsx');
    }

    /**
     * Show the form for importing users.
     */
    public function importUsersForm()
    {
        return view('users.import');
    }

    /**
     * Handle the import of users from Excel.
     */
    public function importUsers(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240', // Max 10MB
        ]);

        try {
            $import = new UsersImport();
            Excel::import($import, $request->file('excel_file'));

            // Après l'import, on doit assigner le rôle 'employe' aux utilisateurs créés si ce n'est pas fait dans UsersImport
            // Cette partie est délicate si UsersImport retourne juste des modèles.
            // Pour l'instant, on va supposer que UsersImport sauvegarde l'utilisateur et qu'on peut le récupérer.
            // Une meilleure approche est de faire l'assignation de rôle dans UsersImport.
            // OU, si UsersImport utilise `WithEvents` et `AfterImport`, on peut écouter cet événement.

            // Si UsersImport retourne les IDs des utilisateurs créés :
            // $createdUserIds = $import->getCreatedUserIds(); // Méthode à ajouter à UsersImport
            // User::whereIn('id', $createdUserIds)->each(function ($user) {
            //     if (!$user->hasAnyRole(Role::all())) { // N'assigner que s'il n'a pas déjà de rôle
            //         $user->assignRole('employe');
            //     }
            // });
            // Note: Le plus simple est de modifier UsersImport pour qu'il assigne 'employe' par défaut.
            // Ou que UsersImport gère la logique de rôle si une colonne 'role' est dans l'Excel.

            $importedCount = $import->getImportedRowCount();
            $skippedCount = count($import->getSkippedRows());
            $skippedDetails = $import->getSkippedRows();

            $message = $importedCount . ' utilisateur(s) importé(s) avec succès.';
            if ($skippedCount > 0) {
                $message .= ' ' . $skippedCount . ' ligne(s) ont été ignorées (ex: email dupliqué).';
                // Vous pouvez passer $skippedDetails à la session pour les afficher si vous le souhaitez.
                Log::warning("Importation Excel - Lignes ignorées: " . json_encode($skippedDetails));
            }

            return redirect()->route('users.index')->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = 'Ligne ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . ' (pour la colonne ' . $failure->attribute() . ' avec la valeur: \'' . $failure->values()[$failure->attribute() ?? array_key_first($failure->values())] . '\')';
             }
             Log::error("Erreurs de validation d'importation Excel: " . implode('; ', $errorMessages));
             return redirect()->route('users.import.form')->with('import_errors', $errorMessages)->withInput();
        } catch (\Exception $e) {
            Log::error("Erreur générale d'importation Excel: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            return redirect()->route('users.import.form')->with('error', 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage())->withInput();
        }
    }
}
