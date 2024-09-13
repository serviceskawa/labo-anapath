<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRefundRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            // Supprimez la contrainte de clé étrangère
            $table->dropForeign(['test_order_id']);
            // Rendez la colonne nullable
            $table->unsignedBigInteger('test_order_id')->nullable()->change();
            // Recréez la contrainte de clé étrangère
            $table->foreignId('invoice_id')->nullable()
                ->constrained('invoices')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreignId('refund_reason_id')->nullable()
                ->constrained('refund_reasons')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            // $table->dropColumn('status');
            // $table->enum('status',['En attente','Aprouvé','Rejeté','Clôturé'])->default('En attente');
            $table->text('refund_method')->nullable();
            $table->text('note')->nullable();
            $table->text('attachment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('refund_requests', function (Blueprint $table) {
            //
        });
    }
}