<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashboxAddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbox_adds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashbox_id')->nullable()
                ->constrained('cashboxes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('date');
            $table->float('amount');
            $table->foreignId('bank_id')->nullable()
                ->constrained('banks')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->bigInteger('cheque_number')->nullable();
            $table->text('description')->nullable();
            $table->text('attachement')->nullable();
            $table->foreignId('invoice_id')->nullable()
                ->constrained('invoices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('cashbox_adds');
    }
}
