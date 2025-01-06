<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeyPatientsPrestation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestation_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->after('id');
            $table->unsignedBigInteger('prestation_id')->after('patient_id');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestation_orders', function (Blueprint $table) {
            //
        });
    }
}
