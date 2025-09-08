<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTestPathologyMacrosDefaultValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_pathology_macros', function (Blueprint $table) {
            $table->boolean('circulation')->default(1)->change();
            $table->boolean('embedding')->default(1)->change();
            $table->boolean('microtomy_spreading')->default(1)->change();
            $table->boolean('staining')->default(1)->change();
            $table->boolean('mounting')->default(1)->change();
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
            $table->boolean('circulation')->default(0)->change();
            $table->boolean('embedding')->default(0)->change();
            $table->boolean('microtomy_spreading')->default(0)->change();
            $table->boolean('staining')->default(0)->change();
            $table->boolean('mounting')->default(0)->change();
        });
    }
}
