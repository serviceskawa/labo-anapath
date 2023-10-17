<?php

namespace App\Mail;

use App\Models\SettingApp;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShareDocMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data,$path)
    {
        $this->data = $data;
        $this->path = $path;
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
            ->subject('Notification : Un document vous a été partagé')
            ->view('emails.share_notification')
            ->attachFromStorage($this->path)
            ->with(['data'=>$this->data,'lab'=>$lab_name,'docPath'=>$this->path]);
    }
}
