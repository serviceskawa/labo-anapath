<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToTestPathologyMacros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_pathology_macros', function (Blueprint $table) {
            Schema::table('test_pathology_macros', function (Blueprint $table) {
                $table->unique('id_test_pathology_order', 'unique_id_test_pathology_order');
            });
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
            Schema::table('test_pathology_macros', function (Blueprint $table) {
                $table->dropUnique('unique_id_test_pathology_order');
            });
        });
    }
}
