<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DailyDatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Faire une sauvegarde de la base de données';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // nous utilisons la commande mysqldump pour créer une sauvegarde de la base de données MySQL.
        //  Le nom du fichier de sauvegarde est généré en utilisant la date et l'heure actuelles.
        // Le fichier de sauvegarde est enregistré dans le répertoire "storage/app/public/backups/".

        $fileName = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $command = sprintf('mysqldump -u%s -p%s %s > %s', env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), storage_path('app/public/backup/' . $fileName));
        exec($command);
    }
}
