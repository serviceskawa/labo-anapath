<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryPrestationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_prestations')->delete();
        $category_prestations = [
            [
                'id' => '1', 'name' => 'Consultation', 'slug' => Str::slug("Consultation"),
            ], [
                'id' => '2', 'name' => 'Chimiothérapie', 'slug' => Str::slug("Chimiothérapie"),
            ], [
                'id' => '3', 'name' => 'Actes', 'slug' => Str::slug("Actes"),
            ],
        ];
        DB::table('category_prestations')->insert($category_prestations);
    }
}
