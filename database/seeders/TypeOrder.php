<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeOrder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_orders')->delete();
        $type_orders = [
            [
                'id' => '1', 'title' => 'Histologie', 'slug' => Str::slug("Histologie"),
            ],
            [
                'id' => '2', 'title' => 'Immuno Externe', 'slug' => Str::slug("Immuno Exterme"),
            ],
            [
                'id' => '3', 'title' => 'Immuno Interne', 'slug' => Str::slug("Immuno Interne"),
            ],
            [
                'id' => '4', 'title' => 'Cytologie', 'slug' => Str::slug("Cytologie"),
            ],
        ];
        DB::table('type_orders')->insert($type_orders);
    }
}
