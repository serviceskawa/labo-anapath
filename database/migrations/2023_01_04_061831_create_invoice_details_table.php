<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->nullable()
                ->constrained('invoices')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('test_id')->nullable()
                ->constrained('tests')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('test_name', 100)->nullable();
            $table->float('price')->nullable();
            $table->float('discount')->nullable();
            $table->float('total')->nullable();
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
        Schema::dropIfExists('invoice_details');
    }
}
