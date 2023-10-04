<?php

namespace App\Mail;

use App\Models\SettingApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AssignedReviewMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
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

         $this->from($from_adresse->value, $from_name->value) // L'expéditeur
            ->subject('Notification : Vous avez été ajouté comme réviseur de compte rendu')
            ->view('emails.review_notification')
            ->with(['data'=>$this->data,'lab'=>$lab_name]);
    }
}
