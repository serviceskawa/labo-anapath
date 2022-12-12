<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePermissionToNullableInHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->string('telephone')->nullable()->change();
            $table->float('commission')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->string('telephone')->nullable()->change();
            $table->float('commission')->nullable()->change();
        });
    }
}
