<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeConsultationFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('type_consultation_files')->delete();
        $type_consultation_files = [
            [
                'id' => '1', 'title' => 'Bilans biologiques', 'slug' => Str::slug("Bilans biologiques"),
            ], [
                'id' => '2', 'title' => 'Bilans biologiques tumoraux', 'slug' => Str::slug("Bilans biologiques tumoraux"),
            ], [
                'id' => '3', 'title' => 'Bilans morphologiques', 'slug' => Str::slug("Bilans morphologiques"),
            ], [
                'id' => '4', 'title' => 'Anatomopathologie', 'slug' => Str::slug("Anatomopathologie"),
            ], [
                'id' => '5', 'title' => 'Bilan de validation', 'slug' => Str::slug("Bilan de validation"),
            ], [
                'id' => '6', 'title' => 'Protocole', 'slug' => Str::slug("Protocole"),
            ], [
                'id' => '7', 'title' => 'Chimiothérapie', 'slug' => Str::slug("Chimiothérapie"),
            ], [
                'id' => '8', 'title' => 'Chirurgie', 'slug' => Str::slug("Chirurgie"),
            ], [
                'id' => '9', 'title' => 'Radiothérapie', 'slug' => Str::slug("Radiothérapie"),
            ], [
                'id' => '10', 'title' => 'Anapath', 'slug' => Str::slug("Anapath"),
            ], [
                'id' => '11', 'title' => 'Bilans de suivi', 'slug' => Str::slug("Bilans de suivi"),
            ],
        ];
        DB::table('type_consultation_files')->insert($type_consultation_files);
    }
}
