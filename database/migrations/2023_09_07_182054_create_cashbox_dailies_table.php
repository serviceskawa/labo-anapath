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
            // $table->unsignedBigInteger('cashbox_id'); 
            // $table->integer('opening_balance'); 
            // $table->integer('close_balance'); 
            $table->id();
            $table->unsignedBigInteger('cashbox_id');
            $table->decimal('opening_balance', 10, 2);
            $table->decimal('close_balance', 10, 2);
            $table->decimal('cash_calculated', 10, 2)->default(null);
            $table->decimal('cash_confirmation', 10, 2)->default(null);
            $table->decimal('cash_ecart', 10, 2)->default(null);
            $table->decimal('mobile_money_calculated', 10, 2)->default(null);
            $table->decimal('mobile_money_confirmation', 10, 2)->default(null);
            $table->decimal('mobile_money_ecart', 10, 2)->default(null);
            $table->decimal('cheque_calculated', 10, 2)->default(null);
            $table->decimal('cheque_confirmation', 10, 2)->default(null);
            $table->decimal('cheque_ecart', 10, 2)->default(null);
            $table->decimal('virement_calculated', 10, 2)->default(null);
            $table->decimal('virement_confirmation', 10, 2)->default(null);
            $table->decimal('virement_ecart', 10, 2)->default(null);
            $table->decimal('total_calculated', 10, 2)->default(null);
            $table->decimal('total_confirmation', 10, 2)->default(null);
            $table->decimal('total_ecart', 10, 2)->default(null);
            $table->integer('status'); 
            $table->unsignedBigInteger('user_id');
            // Clé étrangère
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');            
            $table->foreign('cashbox_id')->references('id')->on('cashboxes')->onUpdate('cascade')->onDelete('restrict');            
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