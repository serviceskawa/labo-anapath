<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetTestorderColumnNullableInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Supprimez la contrainte de clé étrangère
            $table->dropForeign(['test_order_id']);
            // Rendez la colonne nullable
            $table->unsignedBigInteger('test_order_id')->nullable()->change();
            // Recréez la contrainte de clé étrangère
            $table->foreign('test_order_id')->references('id')->on('test_orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            //
        });
    }
}
