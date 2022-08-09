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
            $table->foreignId('test_order_id')
                ->constrained('test_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('test_id') 
                ->constrained('tests')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('test_name');
            $table->float('price');
            $table->float('discount')->nullable();
            $table->float('total');
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
