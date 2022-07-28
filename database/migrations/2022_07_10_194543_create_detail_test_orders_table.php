<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailTestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_test_orders', function (Blueprint $table) {
            $table->id();
            $table->string('test_id');
            $table->string('lib_test');
            $table->float('price');
            $table->float('remise')->nullable();
            $table->float('montant_contrat');
            $table->float('montant_patient');
            $table->float('montant_total');
            $table->foreignId('test_order_id');
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
        Schema::dropIfExists('detail_test_orders');
    }
}
