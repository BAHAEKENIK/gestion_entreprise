<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employe_id')->constrained('users')->comment('ID de l\'employé qui a soumis la réclamation (auteur)')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('directeur_id')->constrained('users')->comment('ID du directeur à qui la réclamation est adressée (destinataire/gestionnaire)')->onUpdate('cascade')->onDelete('cascade');
            $table->string('sujet'); // Ajout d'un champ sujet pour plus de clarté
            $table->text('description');
            $table->enum('statut', ['soumise', 'en_cours_traitement', 'resolue', 'rejetee'])->default('soumise'); // Corrigé ici
            $table->text('reponse')->nullable();
            $table->timestamp('date_reponse')->nullable(); // Date à laquelle la réponse a été donnée
            $table->timestamps(); // created_at sera la date de soumission
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
