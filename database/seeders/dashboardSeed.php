<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use App\Models\Role;
use Illuminate\Database\Seeder;

class dashboardSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ressource = Ressource::updateOrCreate([
            'titre' => "settingDashboard",
            'slug' => "dashboard",
        ]);

        $permission = Permission::updateOrCreate([
            'titre' =>"view dashboard",
            'slug' => "view-dashboard",
            'operation_id' => 1,
            'ressource_id' => $ressource->id,
        ]);

        $role = Role::updateOrCreate(
            ['name' => 'Dashboard', 'slug' => 'dashboard'],
            ['created_by' => 5, 'description' =>'setting dashboard']
        );
        $role->permissions()->attach($permission);
    }
}
