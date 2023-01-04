<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_order_id')->nullable()
                ->constrained('test_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('client_name', 100)->nullable();
            $table->text('client_address')->nullable();
            $table->float('subtotal')->nullable();
            $table->float('discount')->nullable();
            $table->float('total')->nullable();
            $table->text('url')->nullable();
            $table->boolean('paid')->default(false);
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
        Schema::dropIfExists('invoices');
    }
}
