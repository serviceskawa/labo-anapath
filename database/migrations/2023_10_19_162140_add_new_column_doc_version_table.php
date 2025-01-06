<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnDocVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doc_versions', function (Blueprint $table) {
            $table->integer('version')->default(1);
            $table->foreignId('role_id')->nullable()// Utilisateur qui a ouvert le ticket
                ->constrained('roles')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->foreignId('user_id')->nullable()// Utilisateur qui a ouvert le ticket
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            $table->bigInteger('file_size')->nullable();
            $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doc_versions', function (Blueprint $table) {
            //
        });
    }
}
