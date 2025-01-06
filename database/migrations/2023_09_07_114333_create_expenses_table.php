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
            $table->unsignedBigInteger('user_id'); // Clé étrangère vers la table des utilisateurs
            $table->string('item_name'); 
            $table->unsignedBigInteger('item_id'); // nom de l'article de la dépense (exemple de champ texte)
            $table->decimal('total_amount');
            $table->decimal('unit_price'); 
            $table->decimal('quantity'); 
            $table->unsignedBigInteger('supplier_id'); 

            $table->unsignedBigInteger('expense_categorie_id'); // Clé étrangère vers la table des catégories de dépenses
            $table->string('receipt')->nullable(); // Pièce justificative (peut être NULL)
            $table->unsignedBigInteger('cashbox_ticket_id')->nullable(); // Champ facultatif
            $table->integer('paid')->default(0); // Champ payé (0 ou 1, par défaut 0)

            // Clés étrangères
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('item_id')->references('id')->on('articles')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('cashbox_ticket_id')->references('id')->on('cashbox_tickets')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('expense_categorie_id')->references('id')->on('expense_categories')->onUpdate('cascade')->onDelete('restrict');
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