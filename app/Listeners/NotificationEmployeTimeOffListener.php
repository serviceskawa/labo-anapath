<?php

namespace App\Listeners;

use App\Events\NotificationEmployeTimeOffEvent;
use App\Mail\NotificationEmployeTimeOffMail;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotificationEmployeTimeOffListener
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
    public function handle(NotificationEmployeTimeOffEvent $event)
    {
        $userId = $event->userId;
        $timeOff= $event->timeOff;
        $user = Employee::find($userId);

        if ($user->email) {
            # code...
            Mail::to($user->email)->queue(new NotificationEmployeTimeOffMail($user,$timeOff));
        }
    }
}
