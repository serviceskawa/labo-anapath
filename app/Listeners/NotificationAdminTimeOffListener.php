<?php

namespace App\Listeners;

use App\Events\NotificationAdminTimeOffEvent;
use App\Mail\NotificationAdminTimeOffMail;
use App\Models\SettingApp;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotificationAdminTimeOffListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NotificationAdminTimeOffEvent $event)
    {
        $employe = $event->employe;

        $admin_mails = SettingApp::where('key','admin_mails')->first();
        $service = SettingApp::where('key','services')->first();
        $mails = explode('|',$admin_mails->value);
        $services = explode('|',$service->value);

        if (in_array("conge",$services)) {
            foreach ($mails as $mail) {
                try {
                    Mail::to($mail)->queue(new NotificationAdminTimeOffMail($employe));
                } catch (\Throwable $th) {
                    dd($th->getMessage());
                }
            }
        }
    }
}
