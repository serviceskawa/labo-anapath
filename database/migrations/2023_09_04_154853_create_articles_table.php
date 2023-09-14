<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('article_name');
            $table->text('description');
            $table->bigInteger('prix')->default(0);
            $table->bigInteger('quantity_in_stock');
            $table->date('expiration_date')->nullable();
            $table->integer('lot_number')->nullable();
            $table->bigInteger('minimum')->default(1);
            $table->unsignedBigInteger('unit_measurement_id');
            $table->foreign('unit_measurement_id')->references('id')->on('unit_Measurements')->onUpdate('cascade')->onDelete('restrict');
            $table->softDeletes();
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
        Schema::dropIfExists('articles');
    }
}