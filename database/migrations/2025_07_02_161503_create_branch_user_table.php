<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_user', function (Blueprint $table) {
            $table->id(); // Clé primaire
            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID de l’utilisateur lié'); // Clé étrangère vers users
              $table->foreignId('user_id')->nullable()
                ->comment('ID de l’utilisateur lié')
                // ->constrained('users')
                // ->onUpdate('cascade')
                // ->onDelete('restrict')
                ; // Clé étrangère vers users

            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade')->comment('ID de la branche liée'); // Clé étrangère vers branches
            $table->boolean('is_default')->default(true)->comment('Indique si cette branche est la branche par défaut de l’utilisateur'); // Détermine la branche par défaut
            $table->timestamps(); // Horodatage
            $table->softDeletes(); // Horodatage
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_user');
    }
}
