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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->enum('statut',['soumise','en_cours_traitement'.'resolue','regetee']);
            $table->text('reponse')->nullable();
            $table->foreignId('employe_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('directeur_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
