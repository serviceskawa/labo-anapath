<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id');
            $table->foreignId('doctor_id');
            $table->foreignId('hospital_id');
            $table->string('reference_hopital')->nullable();
            $table->foreignId('contrat_id');
            $table->string('archive')->nullable();
            $table->float('subtotal')->nullable();
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
        Schema::dropIfExists('test_orders');
    }
}
