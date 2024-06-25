<?php

namespace App\Mail;

use App\Models\SettingApp;
use App\Models\TestOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailReportNonFait extends Mailable
{
    use Queueable, SerializesModels;

    protected $testOrder;
    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TestOrder $testOrder, $user)
    {
        $this->testOrder = $testOrder;
        $this->user = $user;
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
            'doctorName' => $this->user->firstname . " " . $this->user->lastname,
            'reportCode' => $this->testOrder->report->code,
            'date_create' => $this->testOrder->created_at,
        ];

        $this->from($from_adresse->value, $from_name->value)
            ->subject("Rappel de compte rendu - Demande [{$this->testOrder->code}]")
            ->view('emails.report_non_fait')
            ->with(['data' => $data, 'lab' => $lab_name]);
    }
}
