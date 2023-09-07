<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleCaissier extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleDB = Role::updateOrCreate(
            ['name' => 'Caissier', 'slug' => 'caissier'],
            ['created_by' => 5, 'description' =>'utilisateur avec le role caissier']
        );
        $permission = [
            "titre" => 'view pay_button',
            "slug" => 'view-pay-button',
            "operation_id" => 1,
            "ressource_id" => 14
        ];
        // $permissionDB = DB::table('permissions')->insert($permission);
        $permissionDB = Permission::create($permission);
        $roleDB->permissions()->attach($permissionDB);
    }
}
