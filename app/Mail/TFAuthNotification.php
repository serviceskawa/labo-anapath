<?php

namespace App\Mail;

use App\Models\SettingApp;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TFAuthNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        ->subject('Connection Confirmation de code')
        ->view('emails.tfauth_notification')
        ->with(['user'=>$this->user,'lab'=>$lab_name]);
        //dd($this);
    }
}
