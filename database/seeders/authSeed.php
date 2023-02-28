<?php

namespace Database\Seeders;

use App\Models\operations;
use App\Models\permissions;
use App\Models\ressources;
use App\Models\roles;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class authSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $role = roles::updateOrCreate(
            ['name' => 'rootuser', 'slug' => 'rootuser'],
            ['created_by' => 1, 'description' =>'Super utilisateur']
        ); 

        $permission = permissions::all();

        $role->permissions()->attach($permission);

        $last_role = roles::whereSlug('rootuser')->first();

        $roles = [$last_role->id];

        $user = User::updateorcreate(["email" =>'test1@cci.bj'],[
            "name" => 'Root',
            "password" =>  Hash::make('ccibenin@root'), 
            'role' => 'null'
        ]);
        $user->roles()->attach($roles);
        
        $permsTab = [];
        foreach ($roles as $key => $role_id) {
            $role = roles::findorfail($role_id);
            foreach ($role->permissions as $key => $perms) {
                $permsTab[] = $perms->id;
            }

        }
        $user->permissions()->attach($permsTab);

    }
}
