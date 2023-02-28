<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Setting;
use App\Models\TestOrder;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderBy('date','DESC')->get();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testOrders = TestOrder::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('invoices.create', compact('testOrders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $this->validate($request, [
            'test_orders_id' => 'required|exists:test_orders,id',
            'invoice_date' => 'required',
        ]);

        // Recupération de la ligne corespondante à la demande d'examen
        $testOrder = TestOrder::FindOrFail($data['test_orders_id']);

        $tests = $testOrder->details()->get();

        // Verification de l'existance
        if (empty($testOrder)) {
            return back()->with('error', "Cette demande d'examen n'existe pas. Veuillez réessayer! ");
        }


        $invoiceExist = $testOrder->invoice()->first();

        if (!empty($invoiceExist)) {
            return back()->with('error', "Il existe deja une facture pour cette demande. Veuillez réessayer! ");
        }
        $code_facture = generateCodeFacture();
        try {
            // Creation de la facture
            $invoice = Invoice::create([
                "test_order_id" => $data['test_orders_id'],
                "date" => $data['invoice_date'],
                "patient_id" => $testOrder->patient_id,
                "code" => "FA" . $testOrder->code,
                "subtotal" => $testOrder->subtotal,
                "discount" => $testOrder->discount,
                "total" => $testOrder->total,
                "code" => $code_facture
            ]);

            if (!empty($invoice)) {
                // Creation des details de la facture
                foreach ($tests as $value) {
                    InvoiceDetail::create([
                        "invoice_id" => $invoice->id,
                        "test_id" => $value->test_id,
                        "test_name" => $value->test_name,
                        "price" => $value->price,
                        "discount" => $value->discount,
                        "total" => $value->total,
                    ]);
                }
            }

            return redirect()->route('invoice.index')->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $invoice = Invoice::findorfail($id);
        $setting = Setting::find(1);
        if (empty($invoice)) {
            return back()->with('error', "Cette facture n'existe pas. Verifiez et réessayez svp ! ");
        }

        config(['app.name' => $setting->titre]);
        return view('invoices.show', compact('invoice', 'setting'));
    }

    function print($id)
    {
        $invoice = Invoice::findorfail($id);
        $setting = Setting::find(1);

        if (empty($invoice)) {
            return back()->with('error', "Cette facture n'existe pas. Verifiez et réessayez svp ! ");
        }
        config(['app.name' => $setting->titre]);
        return view('invoices.print', compact('invoice', 'setting'));
    }

    public function storeFromOrder($id)
    {
        $testOrder = TestOrder::FindOrFail($id);

        // Verification de l'existance
        if (empty($testOrder)) {
            return back()->with('error', "Cette demande d'examen n'hexiste pas. Veuillez réessayer! ");
        }

        $invoiceExist = $testOrder->invoice()->first();

        if (!empty($invoiceExist)) {
            return back()->with('error', "Il existe deja une facture pour cette demande. Veuillez réessayer! ");
        }

        $tests = $testOrder->details()->get();

        try {
            // Creation de la facture
            $invoice = Invoice::create([
                "test_order_id" => $id,
                "date" => date('Y-m-d'),
                "patient_id" => $testOrder->patient_id,
                "client_name" => $testOrder->patient->firstname . ' ' . $testOrder->patient->firstname,
                "subtotal" => $testOrder->subtotal,
                "discount" => $testOrder->discount,
                "total" => $testOrder->total,
                "code" => "FA" . $testOrder->code,
            ]);

            if (!empty($invoice)) {
                // Creation des details de la facture
                foreach ($tests as $value) {
                    InvoiceDetail::create([
                        "invoice_id" => $invoice->id,
                        "test_id" => $value->test_id,
                        "test_name" => $value->test_name,
                        "price" => $value->price,
                        "discount" => $value->discount,
                        "total" => $value->total,
                    ]);
                }
            }

            return redirect()->route('invoice.show', [$invoice->id])->with('success', " Facture crée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement. Veuillez réessayer svp ! " . $ex->getMessage());
        }
    }

    // Met à jour le statut paid pour le payement
    public function updateStatus($id)
    {
        // if (!getOnlineUser()->can('edit-demandes-examens')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $invoice = Invoice::findorfail($id);

        if ($invoice->paid == 1) {

            return redirect()->back()->with('success', "Cette facture a déjà été payé ! ");
        } else {

            $invoice->fill(["paid" => '1'])->save();

            return redirect()->route('invoice.show', [$invoice->id])->with('success', " Opération effectuée avec succès  ! ");
        }
    }
}
