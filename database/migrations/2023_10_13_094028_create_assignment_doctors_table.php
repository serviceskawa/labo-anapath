<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('report_id')->nullable()
                ->constrained('reports')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('assignment_doctors');
    }
}
