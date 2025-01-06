<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewSettingApp extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Configuration d'email
        DB::table('setting_apps')->insert([
            ['key' => 'admin_mails', 'value' => ''],
            ['key' => 'services', 'value' => '']
        ]);
    }
}
