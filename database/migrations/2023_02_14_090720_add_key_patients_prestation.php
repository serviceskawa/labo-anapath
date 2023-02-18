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
            $table->unsignedBigInteger('patients_id')->after('id');
            $table->unsignedBigInteger('prestations_id')->after('patients_id');
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
