<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('branch_id')
                ->nullable()
                ->after('id') // Ajoute juste après la colonne id (tu peux ajuster)
                ->constrained('branches') // Contrainte vers table branches
                ->nullOnDelete() // Si la branche est supprimée, mettre à null
                ->comment("Identifiant de la branche principale ou active de l'utilisateur"); // 📝 commentaire explicite
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']); // Supprime la contrainte
            $table->dropColumn('branch_id'); // Supprime la colonne
        });
    }
}
