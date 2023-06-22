<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppelByReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appel_by_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->nullable()
                ->constrained('title_reports')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('appel_id');
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
        Schema::dropIfExists('appel_by_reports');
    }
}
