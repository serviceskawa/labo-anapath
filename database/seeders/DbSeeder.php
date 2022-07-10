<?php

namespace Database\Seeders;

use App\Models\CategoryTest;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DbSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $faker = Faker::create();
        
        //CREATION UTILISATEUR
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@admin.com',
            'password' => Hash::make('P@ssw0rd'),
    
        ]);


        //CREATION CATEGORIE TEST
        foreach(range(1,5) as $index){
            $categorie = CategoryTest::create([
                'code' => $faker->numerify('CA-####'),
                'name' => $faker->word,
            ]);
        };
        $categories = CategoryTest::all();


        //CREATION TEST
        // foreach(range(1,10) as $index){
        //     $test = Test::create([
        //         'name' => $faker->company,
        //         'price'=>$faker->randomNumber(5, true),
        //         'category_test_id' => $faker->randomElement($categories->id),
        //     ]);
        // };


        //CREATION PATIENT


        //CREATION HOPITAL


        //CREATION DOCTOR

        //CREATION CONTRAT
    }
}
