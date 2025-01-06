<?php

namespace App\Console\Commands;

use App\Mail\MailReportNonFait;
use App\Models\TestOrder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendReportNonFaitAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:report_non_fait';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Description pour la commande des reports non fait status == 0 il ya 18 jours';

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
    // public function handle()
    // {
    //     // Définir la date limite de 18 jours
    //     $eighteenDaysAgo = Carbon::now()->subDays(18);

    //     // Récupérer les test_orders créées il y a 18 jours ou plus et dont le report status est 0
    //     $testOrders = TestOrder::where('created_at', '<=', $eighteenDaysAgo)
    //         ->whereHas('report', function ($query) {
    //             $query->where('status', 0);
    //         })
    //         ->get();


    //     foreach ($testOrders as $testOrder) {

    //         // Log the order ID to check if the command is running
    //         Log::info('Test Order alert sent for order ID: ' . $testOrder->id);

    //         // Mail::to($mail)->queue(new NotificationAdminTimeOffMail($employe));

    //         Mail::to($testOrder->doctor->email)->queue(new MailReportNonFait($testOrder));
    //     }
    // }


    public function handle()
    {
        // Définir la date limite de 18 jours
        $eighteenDaysAgo = Carbon::now()->subDays(18);

        // Récupérer les test_orders créées il y a 18 jours ou plus et dont le report status est 0
        $testOrders = TestOrder::where('created_at', '<', $eighteenDaysAgo)
            ->whereHas('report', function ($query) {
                $query->where('status', '=', 0);
            })
            ->with(['assignmentDetails.assignment.user'])
            ->get();


        foreach ($testOrders as $order) {
            foreach ($order->assignmentDetails as $detail) {
                $user = $detail->assignment->user;

                // Envoyer l'alerte à l'utilisateur
                Mail::to($user->email_notification)->queue(new MailReportNonFait($order, $user));
            }
        }
    }
}
