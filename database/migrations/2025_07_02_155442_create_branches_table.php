<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // Clé primaire
            $table->string('name')->comment('Nom de la branche ou site de travail'); // ex: Agence Cotonou
            $table->string('code')->nullable()->comment('Code unique identifiant la branche'); // ex: COTONOU-001
            $table->string('location')->nullable()->comment('Emplacement géographique de la branche'); // ex: Cotonou
            $table->timestamps(); // Horodatage created_at et updated_at
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
        Schema::dropIfExists('branches');
    }
}
