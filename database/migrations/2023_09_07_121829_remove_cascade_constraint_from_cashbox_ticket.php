<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCascadeConstraintFromCashboxTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashbox_tickets', function (Blueprint $table) {
            // $table->dropForeign(['cashbox_id']);
            // $table->dropForeign(['cashbox_ticket_id']);
            // $table->foreignId('cashbox_ticket_id')->nullable()
            // ->constrained('cashbox_tickets')
            // ->onUpdate('cascade')
            // ->onDelete('restrict');
            $table->foreignId('cashbox_id')->nullable()
            ->constrained('cashboxes')
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
