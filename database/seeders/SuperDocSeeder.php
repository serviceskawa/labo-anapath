<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class SuperDocSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer la ressource "Super Doctor"
        $res_super_doctor = Ressource::updateOrCreate([
            'titre' => "super_doctor",
            'slug' => "super-doctor",
        ]);

        // Créer la permission pour voir le statut "Super Doctor"
        Permission::updateOrCreate([
            'titre' => "view super_doctor",
            'slug' => "view-super-doctor",
            'operation_id' => 1, // L'ID de l'opération associée (par exemple, 1 pour "view")
            'ressource_id' => $res_super_doctor->id,
        ]);
    }
}
