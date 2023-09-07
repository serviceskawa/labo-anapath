<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashboxAddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashbox_adds', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->float('amount');

            $table->bigInteger('cheque_number')->nullable();
            $table->text('description')->nullable();
            $table->text('attachement')->nullable();
            
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
        Schema::dropIfExists('cashbox_adds');
    }
}
