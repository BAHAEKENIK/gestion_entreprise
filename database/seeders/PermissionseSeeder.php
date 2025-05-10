<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionseSeeder extends Seeder
{
    public function run()
    {
        // Réinitialiser les permissions cachées pour éviter les problèmes de cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
           'role-list', 'role-create', 'role-edit', 'role-delete',
           'user-list', 'user-create', 'user-edit', 'user-delete',
           'user-export', // Pour l'exportation
           // 'user-import', // Optionnel: si vous voulez une permission distincte pour l'import
           // Permissions pour les tâches
           'tache-list-directeur', 'tache-create', 'tache-edit-directeur', 'tache-delete',
           'tache-list-employe', 'tache-realiser',
           // Permissions pour les pointages
           'pointage-list-directeur', 'pointage-gerer-employe',
        ];

        foreach ($permissions as $permissionName) {
             Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Créer les rôles
        $directeurRole = Role::firstOrCreate(['name' => 'directeur']);
        $employeRole = Role::firstOrCreate(['name' => 'employe']);

        // Assigner les permissions au rôle Directeur
        $directeurRole->givePermissionTo([
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'user-list', 'user-create', 'user-edit', 'user-delete',
            'user-export', // 'user-import' si vous l'avez créée
            'tache-list-directeur', 'tache-create', 'tache-edit-directeur', 'tache-delete',
            'pointage-list-directeur',
        ]);

        // Assigner les permissions au rôle Employé
        $employeRole->givePermissionTo([
            'tache-list-employe', 'tache-realiser',
            'pointage-gerer-employe', // Pourrait être le pointage.index et les actions de pointage
        ]);

        // Créer l'utilisateur directeur par défaut si absent
        $userDirecteur = User::firstOrCreate(
            ['email' => 'bahaekenik@gmail.com'],
            [
                'name' => 'Bahae Kenikssi (Directeur)',
                'password' => bcrypt('password123'), // Changez ceci
                'must_change_password' => true,
                'statut' => 'actif',
                'theme' => 'light'
            ]
        );
        $userDirecteur->assignRole($directeurRole); // Assigner le rôle directeur

        $this->command->info('Permissions et Rôles configurés. Utilisateur Directeur créé/mis à jour.');
    }
}
