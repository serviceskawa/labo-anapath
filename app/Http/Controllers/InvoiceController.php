<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use DataTables;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Setting;
use App\Models\SettingInvoice;
use App\Models\TestOrder;
use Carbon\Carbon;
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
        $settingInvoice = SettingInvoice::find(1);
        $setting = Setting::find(1);
        if (empty($invoice)) {
            return back()->with('error', "Cette facture n'existe pas. Verifiez et réessayez svp ! ");
        }

        config(['app.name' => $setting->titre]);
        return view('invoices.show', compact('invoice', 'setting', 'settingInvoice'));
    }

    public function business(){
        //Mois courant
        $nowDay = Carbon::now();

        $curmonth = now()->format('m'); // Récupérer le mois en cours sous forme de chiffre (ex : '01' pour janvier)
        $totalMonth = Invoice::whereMonth('updated_at', $curmonth)->where('paid','=',1)->sum('total');

        //Mois précédent
        $lastMonth = $nowDay->copy()->subMonth()->format('m');
        $totalLastMonth = Invoice::whereMonth('updated_at', $lastMonth)->where('paid','=',1)->sum('total');

        //Jour actuellement
        $today = now()->format('Y-m-d'); // Récupérer la date d'aujourd'hui au format 'YYYY-MM-DD'
        $totalToday = Invoice::whereDate('updated_at', $today)->where('paid','=',1)->sum('total');
        return view('invoices.business', compact('nowDay','totalMonth','totalLastMonth','totalToday'));

        // $data = Consultation::with(['doctor', 'patient', 'type', 'attribuateToDoctor'])->orderBy('created_at', 'desc')->get();

        // return Datatables::of($data)->addIndexColumn()
        // ->editColumn('created_at', function ($data) {
        //     //change over here
        //     return $data->date;
        // })
        // ->addColumn('action', function ($data) {
        //     $btnVoir = '<a type="button" href="' . route('consultation.show', $data->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>';
        //     $btnEdit = ' <a type="button" href="' . route('consultation.edit', $data->id) . '" class="btn btn-warning" title="Mettre à jour examen"><i class="mdi mdi-doctor"></i></a>';
        //     if ($data->status != 1) {
        //         $btnReport = ' <a type="button" href="' . route('details_test_order.index', $data->id) . '" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>';
        //         $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';
        //     } else {
        //         $btnReport = ' <a type="button" href="' . route('report.show', $data->report->id) . '" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>';
        //         $btnDelete = "";
        //     }

        //     if (!empty($data->invoice->id)) {
        //         $btnInvoice = ' <a type="button" href="' . route('invoice.show', $data->invoice->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';
        //     } else {
        //         $btnInvoice = ' <a type="button" href="' . route('invoice.storeFromOrder', $data->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';
        //     }

        //     return $btnVoir . $btnEdit;
        //     // return $btnVoir . $btnEdit . $btnReport . $btnInvoice . $btnDelete;
        // })
        // ->addColumn('patient', function ($data) {
        //     return $data->patient->firstname . ' ' . $data->patient->lastname;
        // })
        // ->addColumn('doctor', function ($data) {
        //     if (empty($data->attribuateToDoctor)) {
        //         $result = "";
        //     } else {
        //         $result = $data->attribuateToDoctor->firstname;
        //     }
        //     return $result;
        // })
        // ->addColumn('type', function ($data) {
        //     if (empty($data->type)) {
        //         return "";
        //     } else {
        //         return $data->type->name;
        //     }
        // })
        // ->rawColumns(['action', 'patient', 'doctor', 'type'])
        // ->make(true);

    }

    function print($id)
    {
        $invoice = Invoice::findorfail($id);
        $settingInvoice = SettingInvoice::find(1);
        $setting = Setting::find(1);

        if (empty($invoice)) {
            return back()->with('error', "Cette facture n'existe pas. Verifiez et réessayez svp ! ");
        }
        config(['app.name' => $setting->titre]);
        return view('invoices.print', compact('invoice', 'setting', 'settingInvoice'));
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
        // if (!getOnlineUser()->can('edit-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $invoice = Invoice::findorfail($id);
        $settingInvoice = SettingInvoice::find(1);

        if ($invoice->paid == 1) {

            return redirect()->back()->with('success', "Cette facture a déjà été payé ! ");
        }
        else {


            if ($settingInvoice->status==1) {
                if ($invoice->test_order_id!=null) {
                    return response()->json(invoiceNormeTest($invoice->test_order_id));
                }
            } else {
                $invoice->fill(["paid" => '1'])->save();
                return redirect()->route('invoice.show', [$invoice->id])->with('success', " Opération effectuée avec succès  ! ");
            }

        }
    }

    public function updatePayment(Request $request)
    {
        $invoice=Invoice::find($request->id);

        if ($invoice->paid ==1) {
            return response()->json('Facture déjà validée');
        } else {
            $invoice->fill([
                'payment'=> $request->payment
            ])->save();
            return response()->json('Mis à jour du payment');
        }

    }

    public function confirmInvoice(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        // $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzNjc4MDc0MDN8VFMwMTAwNTQ2NyIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTY3OTU1OTk2OCwiZXhwIjoxNjk1NDU3NTY4LCJpYXQiOjE2Nzk1NTk5NjgsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.g80Hdsm2VInc7WBfiSvc7MVC34ZEXbwqyJX_66ePDGQ';
        // $ifu = "0202367807403";
        $settingInvoice = SettingInvoice::find(1);
        $accessToken = $settingInvoice->token;
        $ifu = $settingInvoice->ifu;
        $response = $client->request('PUT', 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$request->uid.'/confirm',[
            'headers' => [
                'Authorization' => 'Bearer ' .$accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
        ]);
        $test = json_decode($response->getBody(), true);

        $invoice = Invoice::find($request->invoice_id);

        if (!empty($invoice)) {
            $invoice->fill([
               "paid" => '1',
               "codeMecef" => $test['codeMECeFDGI'],
               "counters" => $test['counters'],
               "dategenerate" => $test['dateTime'],
               "nim" => $test['nim'],
               "qrcode" => $test['qrCode']
           ])->save();
        }

        //'response' => $test['qrCode'],

        return response()->json(['status'=>200, 'type'=>"confirm", "invoice" => $invoice]);
    }

    public function cancelInvoice(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        // $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzNjc4MDc0MDN8VFMwMTAwNTQ2NyIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTY3OTU1OTk2OCwiZXhwIjoxNjk1NDU3NTY4LCJpYXQiOjE2Nzk1NTk5NjgsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.g80Hdsm2VInc7WBfiSvc7MVC34ZEXbwqyJX_66ePDGQ';
        // $ifu = "0202367807403";
        $settingInvoice = SettingInvoice::find(1);
        $accessToken = $settingInvoice->token;
        $ifu = $settingInvoice->ifu;
        $response = $client->request('PUT', 'https://developper.impots.bj/sygmef-emcf/api/invoice/'.$request->uid.'/cancel',[
            'headers' => [
                'Authorization' => 'Bearer ' .$accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
        ]
        );
        $test = json_decode($response->getBody(), true);
        return response()->json(['status'=>200, 'type'=>"cancel", 'response'=> $test]);
    }


}
