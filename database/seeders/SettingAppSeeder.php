<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Configuration générale
        DB::table('setting_apps')->insert([
            ['key' => 'lab_name', 'value' => 'CAAP'],
            ['key' => 'devise', 'value' => 'Votre santé notre priorité'],
            ['key' => 'adress', 'value' => 'Fifadji'],
            ['key' => 'phone', 'value' => '68676568'],
            ['key' => 'email', 'value' => 'admin@caap.bj'],
            ['key' => 'web_site', 'value' => 'gestion.caap.bj'],
            ['key' => 'logo', 'value' => 'path'],
            ['key' => 'favicon', 'value' => 'path'],
            ['key' => 'logo_white', 'value' => 'path'],
            ['key' => 'footer', 'value' => 'Labocaap'],
            // ... Ajoutez d'autres paramètres généraux ici
        ]);

        // Configuration d'email
        DB::table('setting_apps')->insert([
            ['key' => 'email_host', 'value' => 'smtp.gmail.com'],
            ['key' => 'email_port', 'value' => '587'],
            ['key' => 'username', 'value' => 'bmac82745@gmail.com'],
            ['key' => 'password', 'value' => 'cdwgustjbrzmrhus'],
            ['key' => 'encryption', 'value' => 'tls'],
            ['key' => 'from_adresse', 'value' => 'bmac82745@gmail.com'],
            ['key' => 'from_name', 'value' => 'CAAP'],
            // ... Ajoutez d'autres paramètres de configuration d'email ici
        ]);

        // Configuration d'email
        DB::table('setting_apps')->insert([
            ['key' => 'api_sms', 'value' => 'jeton'],
            ['key' => 'link_api_sms', 'value' => 'link'],
            ['key' => 'key_ourvoice', 'value' => 'jeton_ourvoice'],
            ['key' => 'link_ourvoice_call', 'value' => 'link ourvoice_calls'],
            ['key' => 'link_ourvoice_sms', 'value' => 'link ourvoice sms'],
            // ... Ajoutez d'autres paramètres de configuration de la communication mobile ici
        ]);
    }
}
