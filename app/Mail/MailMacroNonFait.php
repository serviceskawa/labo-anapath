<?php

namespace App\Mail;

use App\Models\SettingApp;
use App\Models\TestOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailMacroNonFait extends Mailable
{
    use Queueable, SerializesModels;

    protected $testOrder;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TestOrder $testOrder)
    {
        $this->testOrder = $testOrder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $from_adresse = SettingApp::where('key', 'from_adresse')->first();
        $from_name = SettingApp::where('key', 'from_name')->first();
        $lab_name = SettingApp::where('key', 'lab_name')->first();

        $data = [
            'test_order' => $this->testOrder->code,
            'doctorName' => $this->testOrder->doctor->name,
            'date_create' => $this->testOrder->created_at,
        ];

        $this->from($from_adresse->value, $from_name->value)
            ->subject("Rappel de macro - Demande [{$this->testOrder->code}]")
            ->view('emails.macro_non_fait')
            ->with(['data' => $data, 'lab' => $lab_name]);
    }
}
