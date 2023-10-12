<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('attachment'); // Chemin vers le fichier (attachment)
            $table->boolean('is_current_version')->default(true); // Indique si c'est la version actuelle du document
            $table->unsignedBigInteger('documentation_categorie_id')->nullable(); // Clé étrangère liée à la catégorie
            $table->unsignedBigInteger('user_id'); // Utilisateur qui a téléchargé le document
            // cle etrengere
            $table->foreign('documentation_categorie_id')
                  ->references('id')
                  ->on('documentation_categories')
                  ->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('docs');
    }
}