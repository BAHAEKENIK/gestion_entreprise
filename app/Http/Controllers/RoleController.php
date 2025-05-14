<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB; // Correction: Utilisation de la façade DB

class RoleController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
         $this->middleware('permission:role-create', ['only' => ['create','store']]);
         $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('id','DESC')->paginate(5);
        return view('roles.index',compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $permission = Permission::get();
        return view('roles.create',compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array', // S'assurer que c'est un tableau
            'permission.*' => 'integer|exists:permissions,id' // Valider chaque ID de permission
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Rôle créé avec succès.');
    }

    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Rôle non trouvé.');
        }
        $rolePermissions = $role->permissions; // Utilisation de la relation Eloquent

        return view('roles.show',compact('role','rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Rôle non trouvé.');
        }
        $permission = Permission::get();
        $rolePermissions = $role->permissions->pluck('id')->all(); // Obtenir les IDs des permissions du rôle

        return view('roles.edit',compact('role','permission','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Rôle non trouvé.');
        }

        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$id, // Unique, sauf pour le rôle actuel
            'permission' => 'required|array',
            'permission.*' => 'integer|exists:permissions,id'
        ]);

        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Rôle mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Rôle non trouvé.');
        }
        // Optionnel: Vérifier si le rôle est assigné à des utilisateurs avant de supprimer
        // ou gérer la suppression en cascade des assignations de rôle (Spatie ne le fait pas par défaut).
        // Pour une suppression simple :
        $role->delete(); // Utilise la méthode delete() du modèle Eloquent

        return redirect()->route('roles.index')
                        ->with('success','Rôle supprimé avec succès.');
    }
}
