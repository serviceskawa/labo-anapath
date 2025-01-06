<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class dashfinanceSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::updateorcreate(["email" =>'admin@caap.bj'],[
            "firstname" => 'Admin',
            "lastname" => 'Admin',
            "password" =>  Hash::make('password'),
        ]);

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
            ['created_by' => $user->id, 'description' =>'dashbord finance']
        );
        $role->permissions()->attach($permission);
    }
}
