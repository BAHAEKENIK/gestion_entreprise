<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DirecteurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $directeurExists = User::where('role', 'directeur')->exists();

        if (!$directeurExists) {
            User::create([
                'nom' => 'Kenikssi',
                'prenom' => 'Bahae',
                'telephone' => '0123456789',
                'email' => 'bahaekenik@gmail.com',
                'email_verified_at' => now(),
                'post' => 'Directeur Général',
                'avatar' => 'D:\gestion_entreprise_stage\storage\app\public\téléchargement.jpeg',
                'statut' => 'actif',
                'password' => Hash::make('password'),

                'date_embauche' => now()->subYear(),
                'theme' => 'light',
                'must_change_password' => true,
                'remember_token' => Str::random(10),
            ]);

            $this->command->info('Utilisateur Directeur créé avec succès.');
        } else {
            $this->command->info('Un utilisateur Directeur existe déjà. Aucune action effectuée.');
        }
    }
}
