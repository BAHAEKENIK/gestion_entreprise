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
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->rememberToken()->after('must_change_password'); // Ou après la colonne de votre choix
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropRememberToken();
    });
}
};
