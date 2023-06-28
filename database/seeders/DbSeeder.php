<?php

namespace Database\Seeders;

use App\Models\CategoryTest;
use App\Models\Details_Contrat;
use App\Models\DetailTestOrder;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Patient;
use App\Models\Test;
use App\Models\TestOrder;
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
        Patient::factory(25)->create();

        // $faker = Faker::create();

        // //CREATION UTILISATEUR
        // User::create([
        //     'name' => 'Administrateur',
        //     'email' => 'admin@admin.com',
        //     'password' => Hash::make('P@ssw0rd'),

        // ]);


        // //CREATION CATEGORIE TEST
        // foreach(range(1,5) as $index){
        //     $categorie = CategoryTest::create([
        //         'code' => $faker->numerify('CA-####'),
        //         'name' => $faker->word,
        //     ]);
        // };
        // $categories = CategoryTest::all();


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
        // Hospital::factory(50)->create();

        //CREATION DOCTOR
        // Doctor::factory(50)->create();

        //CEATION TESTORDER
        TestOrder::factory(100)->create();

        //CREATION DETAIL TEST ORDER
        DetailTestOrder::factory(10)->create();

        //CREATION DETAILS CONTRAT
        Details_Contrat::factory(5)->create();



        //CREATION CONTRAT
    }
}
