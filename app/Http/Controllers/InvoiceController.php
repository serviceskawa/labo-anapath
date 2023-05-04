<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use DataTables;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Setting;
use App\Models\SettingInvoice;
use App\Models\TestOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    protected $invoices;
    protected $testOrders;
    protected $settingInvoice;
    protected $invoiceDetails;
    protected $setting;

    public function __construct(Invoice $invoices, Setting $setting, TestOrder $testOrders, SettingInvoice $settingInvoice, InvoiceDetail $invoiceDetails)
    {
        $this->invoices = $invoices;
        $this->testOrders = $testOrders;
        $this->settingInvoice = $settingInvoice;
        $this->invoiceDetails = $invoiceDetails;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = $this->invoices->latest()->get();
        $setting = $this->setting->find(1);
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
        $testOrders = $this->testOrders->all();
        $setting = $this->setting->find(1);
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
        $testOrder = $this->testOrders->FindOrFail($data['test_orders_id']);

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
            $invoice = $this->invoices->create([
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
        $invoice = $this->invoices->findorfail($id);
        $settingInvoice = $this->settingInvoice->find(1);
        $setting = $this->setting->find(1);
        if (empty($invoice)) {
            return back()->with('error', "Cette facture n'existe pas. Verifiez et réessayez svp ! ");
        }

        config(['app.name' => $setting->titre]);
        return view('invoices.show', compact('invoice', 'setting', 'settingInvoice'));
    }

    public function getInvoiceforDatatable(Request $request)
    {

        $periode = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $todayMonth = intval(date('m')); // récupère le mois actuel en tant qu'entier

        $periode = array_slice($periode, 0, $todayMonth); // garde uniquement les mois précédents la date d'aujourd'hui

        return Datatables::of($periode)->addIndexColumn()
            ->editColumn('created_at', function ($periode) {
                //change over here
                //return date('y/m/d',$data->created_at);
                return $periode . ' ' . Carbon::now()->formatLocalized('%G');
            })

            ->addColumn('factures', function ($periode) {
                $monthIndex = array_search($periode, ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']);

                $result = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->sum('total');

                return $result ? $result : 'Néant';

                // return $this->invoices->whereMonth('updated_at', )->sum('total');
            })
            ->addColumn('avoirs', function ($periode) {
                return '';
            })
            ->addColumn('chiffres', function ($periode) {

                $monthIndex = array_search($periode, ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']);

                $chiffre = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->where('paid', '1')->sum('total');
                return $chiffre ? $chiffre : 'Néant';
            })
            ->addColumn('encaissements', function ($periode) {
                $monthIndex = array_search($periode, ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']);

                $resultat = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->where('paid', '1')->sum('total');
                return $resultat ? $resultat : 'Néant';
            })
            ->filter(function ($query) use ($request) {

                if (!empty($request->get('periode'))) {

                    if ($request->get('periode') == 'nowMonth') {
                        $day = Carbon::now()->format('m');
                        // $query->where('updated_at', $day);
                        $query->where(DB::raw('MONTH(updated_at)'), $day);
                    }
                    // $query->where('attribuate_doctor_id', $request->get('attribuate_doctor_id'));
                }
            })
            ->rawColumns(['chiffres', 'avoirs', 'factures', 'encaissements'])
            ->make(true);
    }


    public function business()
    {
        //Mois courant
        $nowDay = Carbon::now();

        $curmonth = now()->format('m'); // Récupérer le mois en cours sous forme de chiffre (ex : '01' pour janvier)
        $totalMonth = $this->invoices->whereMonth('updated_at', $curmonth)->where('paid', '=', 1)->sum('total');

        //Mois précédent
        $lastMonth = $nowDay->copy()->subMonth()->format('m');
        $totalLastMonth = $this->invoices->whereMonth('updated_at', $lastMonth)->where('paid', '=', 1)->sum('total');

        //Jour actuellement
        $today = now()->format('Y-m-d'); // Récupérer la date d'aujourd'hui au format 'YYYY-MM-DD'
        $totalToday = $this->invoices->whereDate('updated_at', $today)->where('paid', '=', 1)->sum('total');
        return view('invoices.business', compact('nowDay', 'totalMonth', 'totalLastMonth', 'totalToday'));
    }

    function print($id)
    {
        $invoice = $this->invoices->findorfail($id);
        $settingInvoice = $this->settingInvoice->find(1);
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
            $invoice = $this->invoices->create([
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
                    $this->invoiceDetails->create([
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

        $invoice = $this->invoices->findorfail($id);
        $settingInvoice = $this->settingInvoice->find(1);

        if ($invoice->paid == 1) {

            return redirect()->back()->with('success', "Cette facture a déjà été payé ! ");
        } else {


            if ($settingInvoice->status == 1) {
                if ($invoice->test_order_id != null) {
                    // return response()->json('cool');
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
        $invoice = $this->invoices->find($request->id);

        if ($invoice->paid == 1) {
            return response()->json('Facture déjà validée');
        } else {
            $invoice->fill([
                'payment' => $request->payment
            ])->save();
            return response()->json('Mis à jour du payment');
        }
    }

    public function confirmInvoice(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $settingInvoice = $this->settingInvoice->find(1);
        $accessToken = $settingInvoice->token;
        $ifu = $settingInvoice->ifu;
        $response = $client->request('PUT', 'https://developper.impots.bj/sygmef-emcf/api/invoice/' . $request->uid . '/confirm', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
        ]);
        $test = json_decode($response->getBody(), true);

        $invoice = $this->invoices->find($request->invoice_id);

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

        return response()->json(['status' => 200, 'type' => "confirm", "invoice" => $invoice]);
    }

    public function cancelInvoice(Request $request)
    {
        $client = new \GuzzleHttp\Client();
        // $accessToken = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6IjAyMDIzNjc4MDc0MDN8VFMwMTAwNTQ2NyIsInJvbGUiOiJUYXhwYXllciIsIm5iZiI6MTY3OTU1OTk2OCwiZXhwIjoxNjk1NDU3NTY4LCJpYXQiOjE2Nzk1NTk5NjgsImlzcyI6ImltcG90cy5iaiIsImF1ZCI6ImltcG90cy5iaiJ9.g80Hdsm2VInc7WBfiSvc7MVC34ZEXbwqyJX_66ePDGQ';
        // $ifu = "0202367807403";
        $settingInvoice = $this->settingInvoice->find(1);
        $accessToken = $settingInvoice->token;
        $ifu = $settingInvoice->ifu;
        $response = $client->request(
            'PUT',
            'https://developper.impots.bj/sygmef-emcf/api/invoice/' . $request->uid . '/cancel',
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ],
            ]
        );
        $test = json_decode($response->getBody(), true);
        return response()->json(['status' => 200, 'type' => "cancel", 'response' => $test]);
    }
}
