<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCascadeConstraintSupplierFromCashboxAdds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cashbox_adds', function (Blueprint $table) {
            $table->foreignId('cashbox_id')->nullable()
            ->constrained('cashboxes')
            ->onUpdate('cascade')
            ->onDelete('restrict');
            $table->foreignId('bank_id')->nullable()
            ->constrained('banks')
            ->onUpdate('cascade')
            ->onDelete('restrict');
            $table->foreignId('invoice_id')->nullable()
            ->constrained('invoices')
            ->onUpdate('cascade')
            ->onDelete('restrict');
            $table->foreignId('user_id')->nullable()
            ->constrained('users')
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
        Schema::table('cashbox_adds', function (Blueprint $table) {
            //
        });
    }
}
