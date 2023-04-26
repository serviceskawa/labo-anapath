<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use App\Models\Role;
use Illuminate\Database\Seeder;

class settingInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ressource = Ressource::updateOrCreate([
            'titre' => "settingInvoice",
            'slug' => "setting-invoice",
        ]);

        $permission = Permission::updateOrCreate([
            'titre' =>"view setting-invoice",
            'slug' => "view-setting-invoice",
            'operation_id' => 1,
            'ressource_id' => $ressource->id,
        ]);

        $role = Role::updateOrCreate(
            ['name' => 'settingInvoice', 'slug' => 'setting-invoice'],
            ['created_by' => 1, 'description' =>'setting invoice']
        );
        $role->permissions()->attach($permission);
    }
}
