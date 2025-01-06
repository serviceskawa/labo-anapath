<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()
                ->constrained('doctors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('date')->nullable();
            $table->enum('priority', ['normal', 'urgent', 'tres urgent'])->nullable()->default('normal');
            $table->enum('status', ['pending', 'approved', 'cancel'])->nullable()->default('pending');
            $table->text('message')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
