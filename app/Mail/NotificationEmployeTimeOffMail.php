<?php

namespace App\Mail;

use App\Models\SettingApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmployeTimeOffMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $timeOff;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$timeOff)
    {
        $this->user = $user;
        $this->timeOff = $timeOff;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from_adresse = SettingApp::where('key','from_adresse')->first();
        $from_name = SettingApp::where('key','from_name')->first();
        $lab_name = SettingApp::where('key','lab_name')->first();

        $data = [
            'user_name'=> $this->user->first_name.' '.$this->user->last_name,
            'start_date' => $this->timeOff->start_date,
            'end_date' => $this->timeOff->end_date,
        ];

         $this->from($from_adresse->value, $from_name->value) // L'expéditeur
            ->subject('Notification : Une demande de congé')
            ->view('emails.employe_notification')
            // ->attachFromStorage($this->path)
            ->with(['data'=>$data,'lab'=>$lab_name]);
    }
}
