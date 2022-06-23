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
            $table->string('reference_hopital')->nullable();
            $table->foreignId('patient_id');
            $table->foreignId('doctor_id');
            $table->foreignId('hospital_id');
            $table->foreignId('contrat_id');
            $table->foreignId('test_id');
            $table->float('price');
            $table->float('remise');
            $table->float('montant_contrat');
            $table->float('montant_patient');
            $table->float('montant_total');
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
