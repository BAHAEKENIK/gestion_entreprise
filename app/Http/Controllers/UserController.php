<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = User::query();

        // N'afficher que les employés si c'est le but de cette page pour le directeur
        $query->whereDoesntHave('roles', function ($q_roles) {
            $q_roles->where('name', 'directeur');
        });
        // Ou si vous voulez tous sauf le directeur connecté :
        // $query->where('id', '!=', auth()->id());


        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q_search) use ($searchTerm) {
                $q_search->where('name', 'LIKE', "%{$searchTerm}%")
                         ->orWhere('email', 'LIKE', "%{$searchTerm}%");
            });
        }

        $users = $query->orderBy('name')->paginate(10);

        return view('users.index',compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        // Rôles assignables (exclure 'directeur' si un directeur ne peut pas créer un autre directeur)
        $roles = Role::where('name', '!=', 'directeur')->pluck('name','name')->all();
        // Ou pour tous les rôles: $roles = Role::pluck('name','name')->all();

        $statuts = ['actif', 'inactif', 'en_conge']; // Pour le select du statut

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
            'roles' => 'required|array', // Assurez-vous que le formulaire envoie un tableau pour les rôles
            'roles.*' => 'string|exists:roles,name', // Valide chaque rôle
            'date_embauche' => 'nullable|date',
            // Pas de validation de mot de passe car il est généré par défaut
        ]);

        $input = $request->only(['name', 'email', 'telephone', 'post', 'statut', 'date_embauche']);
        $defaultPassword = 'password123'; // CHANGEZ CECI pour quelque chose de plus sécurisé ou généré aléatoirement
        $input['password'] = Hash::make($defaultPassword);
        $input['must_change_password'] = true; // Force le changement au premier login
        $input['theme'] = 'light'; // Thème par défaut

        $user = User::create($input);

        // Assigner les rôles sélectionnés
        if ($request->has('roles') && is_array($request->input('roles'))) {
            // S'assurer de ne pas assigner le rôle 'directeur' si ce n'est pas permis
            $rolesToAssign = $request->input('roles');
            if (in_array('directeur', $rolesToAssign) && !$request->user()->hasRole('super-admin')) { // Exemple de logique
                // Gérer l'erreur ou retirer 'directeur'
                // Pour l'instant, on suppose que le formulaire ne propose pas 'directeur' si non permis
            }
            $user->assignRole($rolesToAssign);
        } else {
            $user->assignRole('employe'); // Fallback si aucun rôle n'est envoyé (ne devrait pas arriver avec 'required')
        }

        // Envoyer un email ici si nécessaire avec les identifiants

        return redirect()->route('users.index')
                         ->with('success', 'Utilisateur créé avec succès. Mot de passe par défaut : ' . $defaultPassword);
    }

    // ... vos méthodes show, edit, update, destroy restent globalement les mêmes que dans votre code d'origine ...
    // Adaptez juste les vues edit si besoin pour les nouveaux champs.

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

        $roles = Role::pluck('name','name')->all();
        // Si vous ne voulez pas que le rôle 'directeur' soit modifiable ici (sauf par un super-admin par exemple)
        // if (!auth()->user()->hasRole('super-admin')) {
        //     unset($roles['directeur']);
        // }
        $userRole = $user->roles->pluck('name','name')->all();
        $statuts = ['actif', 'inactif', 'en_conge']; // Assurez-vous que c'est bien là
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
            'password' => 'nullable|string|min:8|same:confirm-password', // min:8 pour Breeze
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
        } else {
            // $input = Arr::except($input,array('password')); // Pas besoin si on ne le met pas dans $input initialement
        }

        $user->update($input);

        // Gérer les rôles
        $newRoles = $request->input('roles');
        // Ajoutez une logique ici si vous voulez empêcher l'auto-assignation de 'directeur'
        $user->syncRoles($newRoles); // syncRoles est plus propre que delete puis assign

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
        if ($user->hasRole('directeur') && !auth()->user()->hasRole('super-admin')) { // Seul un super-admin peut supprimer un directeur
            return redirect()->route('users.index')->with('error', 'Les directeurs ne peuvent être supprimés que par un administrateur système.');
        }

        $user->delete();
        return redirect()->route('users.index')
                         ->with('success','Utilisateur supprimé avec succès.');
    }
}
