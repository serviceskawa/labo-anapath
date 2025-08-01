<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCascadeConstraintFromCashboxTicketDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashbox_ticket_details', function (Blueprint $table) {
            $table->foreignId('cashbox_ticket_id')->nullable()
                ->constrained('cashbox_tickets')
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
        Schema::table('cashbox_ticket_details', function (Blueprint $table) {
            //
        });
    }
}