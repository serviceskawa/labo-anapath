<?php

namespace Database\Seeders;

use App\Models\operation;
use App\Models\permission;
use App\Models\ressource;
use App\Models\role;
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

        $user = User::updateorcreate(["email" =>'test@test.bj'],[
            "firstname" => 'Root',
            "lastname" => 'Root',
            "password" =>  Hash::make('password'),
        ]);

        $role = role::updateOrCreate(
            ['name' => 'rootuser', 'slug' => 'rootuser'],
            ['created_by' => 1, 'description' =>'Super utilisateur']
        );

        $permission = permission::all();

        $role->permissions()->attach($permission);

        $last_role = role::whereSlug('rootuser')->first();

        $roles = [$last_role->id];

        // $user = User::updateorcreate(["email" =>'test@test.bj'],[
        //     "firstname" => 'Root',
        //     "lastname" => 'Root',
        //     "password" =>  Hash::make('password'),
        // ]);
        $user->roles()->attach($roles);

        $permsTab = [];
        foreach ($roles as $key => $role_id) {
            $role = role::findorfail($role_id);
            foreach ($role->permissions as $key => $perms) {
                $permsTab[] = $perms->id;
            }

        }
        $user->permissions()->attach($permsTab);

    }
}
