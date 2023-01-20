<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultationTypeConsultationFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_type_consultation_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')
                ->constrained('consultations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('type_id')
                ->constrained('type_consultations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('type_file_id')->nullable()
                ->constrained('type_consultation_files')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text('path')->nullable();
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
        Schema::dropIfExists('consultation_type_consultation_files');
    }
}
