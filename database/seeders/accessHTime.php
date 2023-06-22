<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class accessHTime extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate(
            ['name' => 'accessHTime', 'slug' => 'accessHTime'],
            ['created_by' => 5, 'description' =>'Super utilisateur with accessHTime']
        );
    }
}
