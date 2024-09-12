<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingAppInfoNewColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Array of data to insert
        $settings = [
            [
                'key' => 'whatsapp_number',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'rccm',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'ifu',
                'value' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert data into the settings table
        DB::table('setting_apps')->insert($settings);
    }
}