<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionseSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
           'role-list', 'role-create', 'role-edit', 'role-delete',
           'user-list', 'user-create', 'user-edit', 'user-delete', 'user-export',
           'tache-list-directeur', 'tache-create', 'tache-edit-directeur', 'tache-delete',
           'tache-list-employe', 'tache-realiser',
           'pointage-list-directeur', 'pointage-gerer-employe',


           'reclamation-list-employe',
           'reclamation-create-employe',
           'reclamation-list-directeur',
           'reclamation-traiter-directeur',
           'reclamation-view',
        ];

        foreach ($permissions as $permissionName) {
             Permission::firstOrCreate(['name' => $permissionName]);
        }

        $directeurRole = Role::firstOrCreate(['name' => 'directeur']);
        $employeRole = Role::firstOrCreate(['name' => 'employe']);

        $directeurRole->givePermissionTo([
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'user-list', 'user-create', 'user-edit', 'user-delete', 'user-export',
            'tache-list-directeur', 'tache-create', 'tache-edit-directeur', 'tache-delete',
            'pointage-list-directeur',
            'reclamation-list-directeur', 'reclamation-traiter-directeur', 'reclamation-view',
        ]);

        $employeRole->givePermissionTo([
            'tache-list-employe', 'tache-realiser',
            'pointage-gerer-employe',
            'reclamation-list-employe', 'reclamation-create-employe', 'reclamation-view',
        ]);

        $userDirecteur = User::firstOrCreate(
            ['email' => 'bahaekenik@gmail.com'],
            [
                'name' => 'Bahae Kenikssi (Directeur)',
                'password' => bcrypt('password123'), 
                'must_change_password' => true,
                'statut' => 'actif',
                'theme' => 'light'
            ]
        );
        $userDirecteur->assignRole($directeurRole);

        $this->command->info('Permissions et Rôles configurés (avec réclamations). Utilisateur Directeur créé/mis à jour.');
    }
}
