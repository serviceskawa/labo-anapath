<?php

namespace Database\Seeders;

use App\Models\Cashbox;
use Illuminate\Database\Seeder;

class CashboxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cash1 = [
            "opening_balance" => 0,
            "current_balance" => 0,
            "type" => 'depense'
        ];
        $cash2 = [
            "opening_balance" => 0,
            "current_balance" => 0,
            "type" => 'vente'
        ];
        // $permissionDB = DB::table('permissions')->insert($permission);
        Cashbox::create($cash1);
        Cashbox::create($cash2);
    }
}
