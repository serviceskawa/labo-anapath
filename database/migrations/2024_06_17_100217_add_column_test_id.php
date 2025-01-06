<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTestId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('details__contrats', function (Blueprint $table) {
            $table->decimal('amount_remise', 10, 2);
            $table->decimal('amount_after_remise', 10, 2);
            $table->unsignedBigInteger('test_id')->nullable();
            $table->foreign('test_id')->references('id')->on('tests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('details__contrats', function (Blueprint $table) {
            $table->decimal('amount_remise', 10, 2);
            $table->decimal('amount_after_remise', 10, 2);
            $table->unsignedBigInteger('test_id');
            $table->foreign('test_id')->references('id')->on('tests');
        });
    }
}