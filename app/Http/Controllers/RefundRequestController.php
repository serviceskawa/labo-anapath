<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\RefundReason;
use App\Models\RefundRequest;
use App\Models\RefundRequestLog;
use App\Models\Setting;
use App\Models\TestOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RefundRequestController extends Controller
{

    protected $setting;
    protected $refundRequest;
    protected $invoices;
    protected $categories;
    protected $testOrder;
    protected $log;

    public function __construct(Setting $setting, RefundRequest $refundRequest, RefundReason $categories,TestOrder $testOrder, Invoice $invoices, RefundRequestLog $log)
    {
        $this->setting = $setting;
        $this->refundRequest = $refundRequest;
        $this->testOrder = $testOrder;
        $this->categories = $categories;
        $this->invoices = $invoices;
        $this->log = $log;
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
        $categories = $this->categories->all();
        $invoices = $this->invoices->where('paid',1)->get();
        $testOrders = $this->testOrder->all();
        $logs = $this->log->all();

        return view('errors_reports.refund.index', compact('setting', 'refundRequests','testOrders','invoices','categories','logs'));
    }

    public function index_categorie()
    {
        $setting = $this->setting->find(1);
        $categories = $this->categories->latest()->get();
        $testOrders = $this->testOrder->all();

        return view('errors_reports.refund.categorie.index', compact('setting', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testOrders = $this->testOrder->all();
        $categories = $this->categories->all();
        $invoices = $this->invoices->where('paid',1)->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('errors_reports.refund.create', compact('testOrders','invoices','categories'));
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
            'invoice_id'=>'required',
            'refund_reason_id'=>'required',

            'montant'=>'required',

            'note'=>'nullable',
        ]);

        $code_facture_avoir = generateCodeFactureAvoir();

        $invoice = $this->invoices->find($data['invoice_id']);

        if ($data['montant'] > $invoice->total) {
            return back()->with('error', "Le montant demandé est supérieur au total enregistré sur la facture. Veuillez réessayer! ");
        }
        $examenFilePath = "";
        if ($request->hasFile('attachement')) {
            $examenFile = $request->file('attachement');
            $examenFilePath = $examenFile->store('/tickets', 'public');
        }


        try {
            $refund = $this->refundRequest->create([
                'invoice_id'=>$data['invoice_id'],
                'refund_reason_id'=>$data['refund_reason_id'],
                'montant'=>$data['montant'],
                'attachment' => $examenFilePath,
                'note'=>$data['note'],
                'code' => $code_facture_avoir
            ]);
            RefundRequestLog::create([
                'refund_request_id'=> $refund->id,
                'user_id' => Auth::user()->id,
                'operation'=> 'En attente'
            ]);

            return redirect()->route('refund.request.index')->with('success',"Demande enregistrée avec success");
        } catch (\Throwable $th) {
            return redirect()->route('refund.request.index')->with('error',"Un problème est suvenu lors de l'enrégistrement".$th->getMessage());
        }
    }

    public function store_categorie(Request $request)
    {
        $data = [
            'description' => $request->description
        ];


        try {
            $this->categories->create([
                'description' => $data['description']
            ]);

            return redirect()->route('refund.request.categorie.index')->with('success',"Raison enregistrée avec success");
        } catch (\Throwable $th) {
            return redirect()->route('refund.request.categorie.index')->with('error',"Un problème est suvenu lors de l'enrégistrement".$th->getMessage());
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
        return response()->json(['data'=>$refundRequest]);
    }

    public function edit_categorie($id)
    {
        $categorie = $this->categories->find($id);
        return response()->json($categorie);
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
            'id' => 'required',

            'montant'=>'required',

            'attachement'=>'nullable',
            'note'=>'nullable',
        ]);
        if ($request->invoice_id) {
            $invoice = $this->invoices->find($request->invoice_id);

            if ($data['montant'] > $invoice->total) {
                return back()->with('error', "Le montant demandé est supérieur au total enregistré sur la facture. Veuillez réessayer! ");
            }
        }
        $examenFilePath = "";
        if ($request->hasFile('attachement')) {
            $examenFile = $request->file('attachement');
            $examenFilePath = $examenFile->store('/tickets', 'public');
        }

        try {

            $refundRequest = $this->refundRequest->find($data['id']);
            $refundRequest->update([
                'invoice_id'=>$request->invoice_id ? $request->invoice_id : $refundRequest->invoice_id,
                'refund_reason_id'=>$request->refund_reason_id ? $request->refund_reason_id : $refundRequest->refund_reason_id,
                'montant'=>$data['montant'] ? $data['montant'] : $refundRequest->montant,
                'attachment' => $examenFilePath ? $examenFilePath : $refundRequest->attachment,
                'note'=>$data['note'] ? $data['note'] : $refundRequest->note,
            ]);
            if ($refundRequest->status == 'Aprouvé' && $refundRequest->attachment) {
                $refundRequest->update([
                    'status'=>'Clôturé'
                ]);
                RefundRequestLog::create([
                    'refund_request_id'=> $refundRequest->id,
                    'user_id' => Auth::user()->id,
                    'operation'=> 'Clôturé'
                ]);
            }
            return redirect()->route('refund.request.index')->with('success',"Mis à jour éffectué avec success");
        } catch (\Throwable $th) {
            return redirect()->route('refund.request.index')->with('error',"Erreur d'enrégistrement".$th->getMessage());
        }
    }

    public function update_categorie(Request $request)
    {
        $data = $this->validate($request,[
            'description'=>'nullable',

            'id'=>'required',
        ]);
        try {
            $categorie = $this->categories->find($data['id']);
            $categorie->update([
                'description'=>$data['description'],
            ]);
            return redirect()->route('refund.request.categorie.index')->with('success',"Mis à jour éffectué avec success");
        } catch (\Throwable $th) {
            return redirect()->route('refund.request.categorie.index')->with('error',"Erreur d'enrégistrement");
        }
    }

    public function updateStatus(Request $request)
    {
        $data = $this->validate($request,[
            'status'=>'nullable',
            'id'=>'required',
        ]);
        try {
            //Récupérer la demande remboursement et faire la mis à jour du status
            $refundRequest = $this->refundRequest->find($data['id']);

            $refundRequest->update([
                'status'=>$data['status']
            ]);

            //Faire l'enrégistrement dans la table log
            RefundRequestLog::create([
                'refund_request_id'=> $refundRequest->id,
                'user_id' => Auth::user()->id,
                'operation'=> $data['status']
            ]);

            $code_facture = generateCodeFacture();
            $invoice = "";
            //si le status envoyé est égal à Approuvé on crée une facture pour cette demande remboursement avec comme référence la facture de vente
            if ($data['status'] == "Aprouvé") {

                $invoice = $this->invoices->create([
                    "date" => Carbon::now()->format('Y-m-d'),
                    "client_name" => $refundRequest->invoice->client_name,
                    "client_address" => $refundRequest->invoice->client_address,
                    "subtotal" => $refundRequest->montant,
                    "discount" => 0,
                    "total" => $refundRequest->montant,
                    'status_invoice' => 1,
                    'reference' => $refundRequest->invoice->id,
                    "code" => $code_facture
                ]);
                //Si une pièce jointe est fournie on côture la demande
                if ($refundRequest->attachment) {
                    $refundRequest->update([
                        'status'=>'Clôturé'
                    ]);
                    RefundRequestLog::create([
                        'refund_request_id'=> $refundRequest->id,
                        'user_id' => Auth::user()->id,
                        'operation'=> 'Clôturé'
                    ]);
                }
                return response()->json(['invoice'=>$invoice->id,'status'=>200,'data'=>1]);
            }else {
                //Si le status envoyé n'est pas approuvé ou En attente on clôture la facture
                if ($data['status'] != "En attente") {
                    $refundRequest->update([
                        'status'=>'Clôturé'
                    ]);
                    RefundRequestLog::create([
                        'refund_request_id'=> $refundRequest->id,
                        'user_id' => Auth::user()->id,
                        'operation'=> 'Clôturé'
                    ]);
                    return response()->json(['data'=>"La demande est rejeté",'status'=>500]);
                }
            }

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

    public function destroy_categorie($id)
    {
        $refundRequest = $this->categories->find($id);
        $refundRequest->delete();
        return back()->with('success',"Suppression éffectuée avec success");
    }
}
