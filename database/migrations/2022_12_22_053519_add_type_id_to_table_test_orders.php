<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeIdToTableTestOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->foreignId('type_order_id')->default(1)
                ->constrained('type_orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->text('test_affiliate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_orders', function (Blueprint $table) {
            //
        });
    }
}
