<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateTestPathologyMacrosIdToUuid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
      // 1. Ajouter la colonne uuid temporaire
        DB::statement("ALTER TABLE test_pathology_macros ADD COLUMN uuid_id CHAR(36) NULL");

        // 2. Générer les UUID pour chaque ligne existante
        $records = DB::table('test_pathology_macros')->get();
        foreach ($records as $record) {
            DB::table('test_pathology_macros')
                ->where('id', $record->id)
                ->update(['uuid_id' => (string) Str::uuid()]);
        }

        // 3. Supprimer l’AUTO_INCREMENT de la colonne `id`
        DB::statement("ALTER TABLE test_pathology_macros MODIFY COLUMN id INT");

        // 4. Supprimer la clé primaire existante
        DB::statement("ALTER TABLE test_pathology_macros DROP PRIMARY KEY");

        // 5. Supprimer l’ancienne colonne `id`
        DB::statement("ALTER TABLE test_pathology_macros DROP COLUMN id");

        // 6. Renommer `uuid_id` en `id` et ajouter la nouvelle clé primaire
        DB::statement("ALTER TABLE test_pathology_macros CHANGE uuid_id id CHAR(36) NOT NULL PRIMARY KEY");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ATTENTION : Inverser cette opération est complexe sans perte de données
        // Ne pas faire sauf si vous avez une sauvegarde
        throw new \Exception('Rollback not supported for UUID migration');
    }
}
