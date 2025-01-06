<?php

namespace App\Console\Commands;

use App\Mail\MailMacroNonFait;
use App\Models\SettingApp;
use App\Models\test_pathology_macro;
use App\Models\TestOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMacroNonFaitAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:macro_non_fait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Macro non fait après 7 jours';

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
        // Récupérer les IDs des test_orders qui sont dans test_pathology_macros
        $testOrderMacroIds = test_pathology_macro::pluck('id_test_pathology_order');

        // Récupérer les test_orders dont l'ID n'est pas dans test_pathology_macros et qui ont été créées il y a plus de 7 jours
        $testOrders = TestOrder::whereNotIn('id', $testOrderMacroIds)
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->get();

        foreach ($testOrders as $testOrder) {

            $emailsString = SettingApp::where('key', 'email_technician')->first();
            // Extraire les adresses email en les séparant par les points-virgules
            $emails = explode(';', $emailsString);

            // Envoyer un email à chaque adresse
            foreach ($emails as $email) {
                $trimmedEmail = trim($email); // Supprimer les espaces inutiles

                if (!empty($trimmedEmail) && filter_var($trimmedEmail, FILTER_VALIDATE_EMAIL)) {
                    Mail::to($trimmedEmail)->queue(new MailMacroNonFait($testOrder));
                }
            }

            // Log the order ID to check if the command is running
            Log::info('Macro alert sent for order ID: ' . $testOrder->id);

            // Mail::to($mail)->queue(new NotificationAdminTimeOffMail($employe));

            // Mail::to($testOrder->doctor->email)->queue(new MailMacroNonFait($testOrder));
        }
    }
}
