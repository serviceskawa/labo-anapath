<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyContratForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->dropForeign(['contrat_id']); // Supprime la contrainte existante
            $table->foreign('contrat_id')
                ->references('id')
                ->on('contrats')
                ->onUpdate('cascade')
                ->onDelete('RESTRICT'); // Ajoute la nouvelle contrainte RESTRICT
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
            $table->dropForeign(['contrat_id']); // Supprime la contrainte
            $table->foreign('contrat_id')
                ->references('id')
                ->on('contrats')
                ->onUpdate('cascade')
                ->onDelete('cascade'); // Rétablit la contrainte précédente en cas de rollback
        });
    }
}
