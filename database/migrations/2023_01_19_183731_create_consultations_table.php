<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->foreignId('doctor_id')->nullable()
                ->constrained('doctors')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('type_consultation_id')->nullable()
                ->constrained('type_consultations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->dateTime('date')->nullable();
            $table->dateTime('next_appointment')->nullable();
            $table->enum('status', ['pending', 'approved', 'cancel'])->nullable()->default('pending');
            $table->text('motif')->nullable();
            $table->text('anamnese')->nullable();
            $table->text('antecedent')->nullable();
            $table->text('examen_physique')->nullable();
            $table->text('diagnostic')->nullable();
            $table->float('fees')->nullable();
            $table->enum('payement_mode', ['carte', 'espèce'])->nullable()->default('espèce');
            $table->json('files')->nullable();
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
        Schema::dropIfExists('consultations');
    }
}
