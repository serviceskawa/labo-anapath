<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('amount'); // Montant de la dépense (exemple de champ décimal)
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table des utilisateurs
            $table->string('description'); // Description de la dépense (exemple de champ texte)
            $table->unsignedBigInteger('expense_categorie_id'); // Clé étrangère vers la table des catégories de dépenses
            $table->string('receipt')->nullable(); // Pièce justificative (peut être NULL)
            $table->unsignedBigInteger('cashbox_ticket_id')->nullable(); // Champ facultatif
            $table->integer('paid')->default(0); // Champ payé (0 ou 1, par défaut 0)
            // Clés étrangères
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('expense_categorie_id')->references('id')->on('expense_categories');
            $table->softDeletes();
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
        Schema::dropIfExists('expenses');
    }
}