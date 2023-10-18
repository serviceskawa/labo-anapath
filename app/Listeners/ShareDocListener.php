<?php

namespace App\Listeners;

use App\Events\ShareDocEvent;
use App\Mail\ShareDocMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ShareDocListener
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
    public function handle(ShareDocEvent $event)
    {
        $user = $event->user;
        $doc = $event->doc;
        $data = [
            'user_name' => $user->fullname(),
            'user_sharer' => $doc->user->fullname(),
            'doc_title' => $doc->title
        ];
        try {

            Mail::to($user->email)->queue(new ShareDocMail($data));
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }
}
