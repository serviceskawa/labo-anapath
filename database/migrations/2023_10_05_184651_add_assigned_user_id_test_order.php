<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignedUserIdTestOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->foreignId('assigned_to_user_id')->nullable() // Utilisateur qui a ajouté le commentaire
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_orders', function (Blueprint $table) {
            //
        });
    }
}
