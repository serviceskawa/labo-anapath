<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCascadeConstraintSupplierFromCashboxTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashbox_tickets', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()
            ->constrained('suppliers')
            ->onUpdate('cascade')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashbox_tickets', function (Blueprint $table) {
            //
        });
    }
}
