<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Check if signature column doesn't already exist
            if (!Schema::hasColumn('invoices', 'signature')) {
                $table->string('signature')
                    ->nullable()
                    ->comment('Signature lors de la validation de la facture')
                    ->after('user_id');
            }
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
            $table->dropColumn('signature');
        });
    }
}
