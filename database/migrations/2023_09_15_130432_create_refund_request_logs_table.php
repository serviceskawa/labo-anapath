<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_request_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refund_request_id')->nullable()
                ->constrained('refund_requests')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->enum('operation',['En attente','Aprouvé','Rejeté','Clôturé']);

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
        Schema::dropIfExists('refund_request_logs');
    }
}
