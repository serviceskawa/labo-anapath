<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColomnMacro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_pathology_macros', function (Blueprint $table) {
            $table->boolean('circulation')->default(false);
            $table->boolean('embedding')->default(false);
            $table->boolean('microtomy_spreading')->default(false);
            $table->boolean('staining')->default(false);
            $table->boolean('mounting')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_pathology_macros', function (Blueprint $table) {
            //
        });
    }
}
