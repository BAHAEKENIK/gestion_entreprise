<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_users_table.php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Breeze utilise 'name'
        $table->string('telephone')->nullable();
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('post')->nullable();
        $table->string('avatar')->nullable()->default('default-avatar.png');
        $table->enum('statut',['actif','inactif','en_conge'])->default('actif');
        // $table->enum('role',['employe','directeur'])->default('employe'); // SUPPRIMER CETTE LIGNE SI SPATIE EST UTILISÉ
        $table->string('password');
        $table->date('date_embauche')->nullable();
        $table->enum('theme', ['light', 'dark'])->default('light');
        $table->boolean('must_change_password')->default(true); // Gardez si vous avez ce flux
        $table->rememberToken(); // IMPORTANT pour Breeze
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
