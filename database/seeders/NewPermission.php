<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class NewPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ressource = Ressource::updateOrCreate([
            'titre' => "pathologist_dashboard",
            'slug' => "pathologist_dashboard",
        ]);

        $permission = Permission::updateOrCreate([
            'titre' =>"view pathologist_dashboard",
            'slug' => "view-pathologist-dashboard",
            'operation_id' => 1,
            'ressource_id' => $ressource->id,
        ]);

        $ressource1 = Ressource::updateOrCreate([
            'titre' => "admin_dashboard",
            'slug' => "admin_dashboard",
        ]);

        $permission1 = Permission::updateOrCreate([
            'titre' =>"view admin_dashboard",
            'slug' => "view-admin-dashboard",
            'operation_id' => 1,
            'ressource_id' => $ressource1->id,
        ]);
        $ressource2 = Ressource::updateOrCreate([
            'titre' => "secretariat_dashboard",
            'slug' => "secretariat_dashboard",
        ]);

        $permission2 = Permission::updateOrCreate([
            'titre' =>"view secretariat_dashboard",
            'slug' => "view-secretariat-dashboard",
            'operation_id' => 1,
            'ressource_id' => $ressource2->id,
        ]);
    }
}
