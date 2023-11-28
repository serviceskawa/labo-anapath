<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestPathologyMacrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_pathology_macros', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->foreignId('id_employee')
                ->references('id')
                ->on('employees')
                ->onUpdate('cascade')
                ->onDelete('RESTRICT');
            $table->foreignId('id_test_pathology_order')
                ->references('id')
                ->on('test_orders')
                ->onUpdate('cascade')
                ->onDelete('RESTRICT');
            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('RESTRICT');
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
        Schema::dropIfExists('test_pathology_macros');
    }
}
