<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumCashboxTable extends Migration
{
    public function up()
    {
        Schema::table('cashboxes', function (Blueprint $table) {
            $table->integer('statut');
        });
    }

    public function down()
    {
        Schema::table('cashboxes', function (Blueprint $table) {
            //
        });
    }
}