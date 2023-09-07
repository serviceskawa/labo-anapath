<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashboxDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbox_dailies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashbox_id'); // Clé étrangère référençant la caisse de vente associée
            $table->integer('opening_balance'); // Solde d'ouverture de caisse pour la journée
            $table->integer('close_balance'); // Solde de fermeture de caisse pour la journée
            $table->integer('status');
            // Clé étrangère
            $table->foreign('cashbox_id')->references('id')->on('cashboxes');
            $table->softDeletes();
            $table->timestamps(); // Champs de date de création et de mise à jour automatiques
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashbox_dailies');
    }
}