<?php

namespace App\Mail;

use App\Models\SettingApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationAdminTimeOffMail extends Mailable
{
    use Queueable, SerializesModels;
    public $employeTimeOff;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employeTimeOff)
    {
        $this->employeTimeOff = $employeTimeOff;
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
            'user_name'=> $this->employeTimeOff->employee->first_name.' '.$this->employeTimeOff->employee->last_name,
          'start_date' => $this->employeTimeOff->start_date,
            'end_date' => $this->employeTimeOff->end_date,
        ];
        

         $this->from($from_adresse->value, $from_name->value) // L'expéditeur
            ->subject('Notification : Une demande de congé')
            ->view('emails.admin_employe_notification')
            // ->attachFromStorage($this->path)
            ->with(['data'=>$data,'lab'=>$lab_name]);
    }
}
