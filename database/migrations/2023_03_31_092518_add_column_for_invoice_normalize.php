<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnForInvoiceNormalize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('codeMecef')->nullable();
            $table->string('counters')->nullable();
            $table->string('dategenerate')->nullable();
            $table->string('nim')->nullable();
            $table->string('qrcode')->nullable();
            $table->enum('payment', ['ESPECES','CHEQUES','MOBILEMONEY','CARTEBANCAIRE','VIREMENT','CREDIT','AUTRE'])->default('ESPECES')->after('total');
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
