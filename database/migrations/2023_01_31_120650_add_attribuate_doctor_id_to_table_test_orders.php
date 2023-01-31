<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttribuateDoctorIdToTableTestOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_orders', function (Blueprint $table) {
            $table->foreignId('attribuate_doctor_id')->nullable()
                ->constrained('doctors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
            $table->foreignId('attribuate_doctor_id')->nullable()
                ->constrained('doctors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
