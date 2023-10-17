<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Ressource;
use Illuminate\Database\Seeder;

class PermissionExpense extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         //Peut ajouter une dépense à la caisse
         $res_add_cashbox_expense = Ressource::updateOrCreate([
            'titre' => "super_depenses",
            'slug' => "super_depenses",
        ]);

        Permission::updateOrCreate([
            'titre' =>"view super_depenses",
            'slug' => "view-super-depenses",
            'operation_id' => 1,
            'ressource_id' => $res_add_cashbox_expense->id,
        ]);
    //Fin Peut ajouter une dépense à la caisse
    }
}
