<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashboxTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbox_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashbox_id')->nullable()
                ->constrained('cashboxes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()
                ->constrained('suppliers')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->float('amount')->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['en attente','approuve','rejete']);
            $table->integer('expense_categorie_id')->nullable();
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
        Schema::dropIfExists('cashbox_tickets');
    }
}
