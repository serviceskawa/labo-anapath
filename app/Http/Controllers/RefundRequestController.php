<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\RefundRequest;
use App\Models\Setting;
use App\Models\TestOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{

    protected $setting;
    protected $refundRequest;
    protected $invoices;
    protected $testOrder;

    public function __construct(Setting $setting, RefundRequest $refundRequest, TestOrder $testOrder, Invoice $invoices)
    {
        $this->setting = $setting;
        $this->refundRequest = $refundRequest;
        $this->testOrder = $testOrder;
        $this->invoices = $invoices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = $this->setting->find(1);
        $refundRequests = $this->refundRequest->latest()->get();
        $testOrders = $this->testOrder->all();

        return view('errors_reports.refund.index', compact('setting', 'refundRequests','testOrders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testOrders = $this->testOrder->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('errors_reports.refund.create', compact('testOrders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request,[
            'test_orders_id'=>'required',

            'montant'=>'required',

            'description'=>'required',
        ]);

        $test_order = $this->testOrder->FindOrFail($data['test_orders_id']);

        if (empty($test_order)) {
            return back()->with('error', "Cette demande d'examen n'existe pas. Veuillez réessayer! ");
        }
        $invoiceExist = $test_order->invoice()->first();

        if (empty($invoiceExist)) {
            return back()->with('error', "Il n'existe pas une facture pour cette demande. Veuillez réessayer! ");
        }

        if ($data['montant'] > $invoiceExist->total) {
            return back()->with('error', "Le montant demandé est supérieur au total enregistré sur la facture. Veuillez réessayer! ");
        }


        try {
            $this->refundRequest->create([
                'test_order_id'=>$data['test_orders_id'],
                'montant'=>$data['montant'],
                'description'=>$data['description'],
            ]);

            return redirect()->route('refund.request.index')->with('success',"Demande enregistrée avec success");
        } catch (\Throwable $th) {
            return redirect()->route('refund.request.index')->with('error',"Un problème est suvenu lors de l'enrégistrement");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function show(RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $refundRequest = $this->refundRequest->find($id);
        return response()->json(['data'=>$refundRequest,'code'=>$refundRequest->order->code]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validate($request,[
            'test_orders_id'=>'required',

            'montant'=>'required',

            'description'=>'required',

            'id'=>'required',
        ]);

        $test_order = $this->testOrder->FindOrFail($data['test_orders_id']);

        if (empty($test_order)) {
            return redirect()->route('refund.request.index')->with('error', "Cette demande d'examen n'existe pas. Veuillez réessayer! ");
        }
        $invoiceExist = $test_order->invoice()->first();

        if (empty($invoiceExist)) {
            return redirect()->route('refund.request.index')->with('error', "Il n'existe pas une facture pour cette demande. Veuillez réessayer! ");
        }

        try {
            $refundRequest = $this->refundRequest->find($data['id']);
            $refundRequest->update([
                'test_order_id'=>$data['test_orders_id'],
                'montant'=>$data['montant'],
                'description'=>$data['description'],
            ]);
            return redirect()->route('refund.request.index')->with('success',"Mis à jour éffectué avec success");
        } catch (\Throwable $th) {
            return redirect()->route('refund.request.index')->with('error',"Erreur d'enrégistrement");
        }
    }

    public function updateStatus(Request $request)
    {
        $data = $this->validate($request,[
            'status'=>'nullable',
            'id'=>'required',
        ]);
        try {
            $refundRequest = $this->refundRequest->find($data['id']);
            $refundRequest->update([
                'status'=>$data['status']
            ]);
            $invoiceExist = $this->invoices->where('test_order_id',$refundRequest->order->id)->where('status_invoice',0)->first();

            $code_facture = generateCodeFacture();
            $invoice = "";

            if (intval($data['status']) == 1) {
                // $invoice = $invoiceExist;
                    $invoice = $this->invoices->create([
                        "test_order_id" => $refundRequest->order->id,
                        "date" => Carbon::now()->format('Y-m-d'),
                        "patient_id" => $refundRequest->order->patient_id,
                        "subtotal" => $refundRequest->montant,
                        "discount" => 0,
                        "total" => $refundRequest->montant,
                        'status_invoice' => 1,
                        'reference' => $invoiceExist->id,
                        "code" => $code_facture
                    ]);
            }


            return response()->json(['data'=>intval($data['status']),'invoice'=>$invoice->id,200]);
        } catch (\Throwable $th) {
            return response()->json($th,500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $refundRequest = $this->refundRequest->find($id);
        $refundRequest->delete();
        return back()->with('success',"Suppression éffectuée avec success");
    }
}
