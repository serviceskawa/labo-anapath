<?php

namespace App\Console\Commands;

use App\Http\Controllers\TestOrderController;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\LogReport;
use App\Models\Report;
use App\Models\Setting;
use App\Models\TestOrder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class OldInvoie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'old:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new invoice for old test order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $invoice = Invoice::all()->first();
        $orders = TestOrder::where('created_at', '<', $invoice->created_at)->get();
        $settings = Setting::find(1);
        $user = User::find(5);
        Auth::login($user);

        foreach ($orders as $key => $test_order) {

            if(!$test_order->code)
            {
                // Génère un code unique
                $code_unique = generateCodeExamen();
                $test_order->fill(["status" => '1', "code" => $code_unique])->save();
            }

            $reportTestOrder = Report::where('test_order_id',$test_order->id)->first();

            if ($reportTestOrder) {
                $reportTestOrder->fill([
                    "code" => "CO" . $test_order->code,
                    "patient_id" => $test_order->patient_id,
                ]);
            }else {
                Report::create([
                    "code" => "CO" . $test_order->code,
                    "patient_id" => $test_order->patient_id,
                    "description" => $settings ? $settings->placeholder : '',
                    "test_order_id" => $test_order->id,
                ]);
            }

            $reportnow = Report::latest()->first();

            $log = new LogReport();
            $log->operation = "Créer un nouveau report";
            $log->report_id = $reportnow->id;
            $log->user_id = $user->id;
            $log->save();

            $invoiceTestOrder = Invoice::where('test_order_id',$test_order->id)->first();

            if ($invoiceTestOrder) {
                $invoiceTestOrder->update([
                    "patient_id" => $test_order->patient_id,
                    "client_name" => $test_order->patient->firstname . ' ' . $test_order->patient->lastname,
                    "client_address" => $test_order->patient->adresse,
                    "subtotal" => $test_order->subtotal,
                    "discount" => $test_order->discount,
                    "total" => $test_order->total,
                ]);
                $tests = $test_order->details()->get();

                foreach ($tests as $value) {
                    if ($value->status ==1) {
                        InvoiceDetail::create([
                            "invoice_id" => $invoiceTestOrder->id,
                            "test_id" => $value->test_id,
                            "test_name" => $value->test_name,
                            "price" => $value->price,
                            "discount" => $value->discount,
                            "total" => $value->total,
                        ]);
                        $value->status =0;
                        $value->save();
                    }
                }

            }else {
                $code_facture = 'REGULAR'.$test_order->id;
                // Creation de la facture
                $invoice = Invoice::create([
                    "test_order_id" => $test_order->id,
                    "date" => date('Y-m-d'),
                    "patient_id" => $test_order->patient_id,
                    "client_name" => $test_order->patient->firstname . ' ' . $test_order->patient->lastname,
                    "client_address" => $test_order->patient->adresse,
                    "subtotal" => $test_order->subtotal,
                    "discount" => $test_order->discount,
                    "total" => $test_order->total,
                    "code" => $code_facture,
                    "created_at" => $test_order->created_at,
                    "updated_at" => $test_order->updated_at,
                ]);
                // Recupération des details de la demande d'examen
                $tests = $test_order->details()->get();
                $items = [];
                // Creation des details de la facture
                foreach ($tests as $value) {
                    if ($value->status ==1) {
                        $detailInvoice = InvoiceDetail::create([
                            "invoice_id" => $invoice->id,
                            "test_id" => $value->test_id,
                            "test_name" => $value->test_name,
                            "price" => $value->price,
                            "discount" => $value->discount,
                            "total" => $value->total,
                            "created_at" => $test_order->created_at,
                            "updated_at" => $test_order->updated_at,
                        ]);
                        $value->status =0;
                        $value->save();
                        $items[]=$detailInvoice;
                    }
                }
            }
        }

        return 'effectué avec success';
    }
}
