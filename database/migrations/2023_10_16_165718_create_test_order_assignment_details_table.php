<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestOrderAssignmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_order_assignment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_order_assignment_id')->nullable()// Utilisateur qui a ouvert le ticket
                ->constrained('test_order_assignments')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('test_order_id')->nullable()// Utilisateur qui a ouvert le ticket
                ->constrained('test_orders')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('note')->nullable();
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
        Schema::dropIfExists('test_order_assignment_details');
    }
}
