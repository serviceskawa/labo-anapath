<?php

namespace App\Listeners;

use App\Events\AssignedReviewer;
use App\Mail\AssignedReviewMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendAssignedReviewerNotification
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
     * @param  \App\Events\AssignedReviewer  $event
     * @return void
     */
    public function handle(AssignedReviewer $event)
    {
        $user_id = $event->userId;
        $user = User::find($user_id);
        $data = $event->data;

        Mail::to($user->email)->queue(new AssignedReviewMail($data));
    }
}
