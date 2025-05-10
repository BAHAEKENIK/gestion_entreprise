<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('taches', function (Blueprint $table) {
        $table->id();
        $table->string('titre');
        $table->text('description')->nullable();
        $table->enum('statut', ['a_faire', 'en_cours', 'terminee', 'en_revision', 'annulee'])->default('a_faire'); // Ajout d'un default
        $table->dateTime('date_assignation')->useCurrent();
        $table->dateTime('date_debut_prevue')->nullable();
        $table->dateTime('date_fin_prevue')->nullable();
        $table->dateTime('date_completion')->nullable(); // CORRIGÉ : Rendre nullable
        $table->string('duree_estimee')->nullable();
        $table->foreignId('employe_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
        $table->foreignId('directeur_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade'); // C'est l'ID du directeur qui a créé/assigné
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taches');
    }
};
