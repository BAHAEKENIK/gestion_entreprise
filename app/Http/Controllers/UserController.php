<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
         $this->middleware('permission:user-create', ['only' => ['create','store', 'importUsersForm', 'importUsers']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
         $this->middleware('permission:user-export', ['only' => ['exportUsers']]);
    }

    public function index(Request $request)
    {
        $query = User::query();

        $query->whereDoesntHave('roles', function ($q_roles) {
            $q_roles->where('name', 'directeur');
        });

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q_search) use ($searchTerm) {
                $q_search->where('name', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('name')->paginate($request->input('per_page', 7));

        return view('users.index',compact('users'))
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
        $defaultPassword = 'password123';
        $input['password'] = Hash::make($defaultPassword);
        $input['must_change_password'] = true;
        $input['theme'] = 'light';

        $user = User::create($input);

        if ($request->has('roles') && is_array($request->input('roles'))) {
            $rolesToAssign = $request->input('roles');
            if (in_array('directeur', $rolesToAssign) && !$request->user()->hasRole('super-admin')) {
                $rolesToAssign = array_diff($rolesToAssign, ['directeur']);
            }
            $user->assignRole($rolesToAssign);
        } else {
            $user->assignRole('employe');
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
            'password' => 'nullable|string|min:8|confirmed',
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

        if (in_array('directeur', $newRoles) &&
            !in_array('directeur', $user->getRoleNames()->toArray()) &&
            !$request->user()->hasRole('super-admin')) {
             return redirect()->back()
                             ->withErrors(['roles' => 'Vous n\'êtes pas autorisé à assigner le rôle "directeur".'])
                             ->withInput();
        } else if (in_array('directeur', $user->getRoleNames()->toArray()) &&
                   !in_array('directeur', $newRoles) &&
                   !$request->user()->hasRole('super-admin')) {
             return redirect()->back()
                             ->withErrors(['roles' => 'Vous ne pouvez pas retirer le rôle "directeur" à cet utilisateur.'])
                             ->withInput();
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

    public function exportUsers()
    {
        return Excel::download(new UsersExport, 'utilisateurs_employes_'.date('Y-m-d_H-i').'.xlsx');
    }

    public function importUsersForm()
    {
        return view('users.import');
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $import = new UsersImport();
            Excel::import($import, $request->file('excel_file'));
            $importedCount = $import->getImportedRowCount();
            $skippedRows = $import->getSkippedRowsLog();
            $skippedCount = count($skippedRows);

            $message = $importedCount . ' utilisateur(s) importé(s) avec succès.';
            if ($skippedCount > 0) {
                $message .= ' ' . $skippedCount . ' ligne(s) ont été ignorées.';
                session()->flash('import_skipped_details', $skippedRows);
                Log::warning("Importation Excel - Lignes ignorées: " . json_encode($skippedRows));
            }

            return redirect()->route('users.index')->with('success', $message);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = 'Ligne ' . $failure->row() . ': ' . implode(', ', $failure->errors()) . ' (colonne ' . $failure->attribute() . ', valeur: \'' . ($failure->values()[$failure->attribute()] ?? 'N/A') . '\')';
             }
             Log::error("Erreurs de validation d'importation Excel (globale): " . implode('; ', $errorMessages));
             $redirectRoute = $request->input('from_modal') ? 'users.index' : 'users.import.form';
             return redirect()->route($redirectRoute)
                              ->with('import_errors', $errorMessages)
                              ->withInput();
        } catch (\Exception $e) {
            Log::error("Erreur générale d'importation Excel: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
            $redirectRoute = $request->input('from_modal') ? 'users.index' : 'users.import.form';
            return redirect()->route($redirectRoute)
                              ->with('error', 'Une erreur est survenue lors de l\'importation: ' . $e->getMessage())
                              ->withInput();
        }
    }
}
