<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetPatientColumnNullableInvoiceTable extends Migration
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
            $table->dropForeign(['patient_id']);
            // Rendez la colonne nullable
            $table->unsignedBigInteger('patient_id')->nullable()->change();
            // Recréez la contrainte de clé étrangère
            $table->foreign('patient_id')->references('id')->on('patients');

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
