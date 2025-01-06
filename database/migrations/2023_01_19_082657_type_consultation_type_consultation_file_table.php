<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TypeConsultationTypeConsultationFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_consultation_type_consultation_file', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')
                ->constrained('type_consultations')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('type_file_id')->nullable()
                ->constrained('type_consultation_files')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        //
    }
}
