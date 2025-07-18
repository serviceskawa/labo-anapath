<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailsContratsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details__contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')
                ->constrained('contrats')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('pourcentage')->nullable();
            $table->foreignId('category_test_id')
                ->constrained('category_tests')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * id
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details__contrats');
    }
}
