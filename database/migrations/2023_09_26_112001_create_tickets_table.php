<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()// Utilisateur qui a ouvert le ticket
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->string('subject'); // Sujet du ticket
            $table->text('description'); // Description détaillée du problème
            $table->string('ticket_code')->unique(); // Code unique du ticket
            $table->boolean('is_resolved')->default(false); // Indique si le ticket est résolu
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
        Schema::dropIfExists('tickets');
    }
}
