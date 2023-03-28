<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use App\Models\Role;
use Illuminate\Database\Seeder;

class dashfinanceSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ressource = Ressource::updateOrCreate([
            'titre' => "dashbord_finance",
            'slug' => "dashbord-finance",
        ]);

        $permission = Permission::updateOrCreate([
            'titre' =>"view dashbord_finance",
            'slug' => "view-dashbord-finance",
            'operation_id' => 1,
            'ressource_id' => $ressource->id,
        ]);

        $role = Role::updateOrCreate(
            ['name' => 'dashbordFinance', 'slug' => 'dashbord-finance'],
            ['created_by' => 1, 'description' =>'dashbord finance']
        );
        $role->permissions()->attach($permission);
    }
}
