<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxAdd;
use App\Models\CashboxDaily;
use App\Models\Consultation;
use DataTables;
use Illuminate\Support\Str;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\RefundRequest;
use App\Models\Setting;
use App\Models\SettingInvoice;
use App\Models\TestOrder;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest; // Renommez la classe Request de Guzzle pour éviter les conflits avec la classe Request de Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables as FacadesDataTables;
use App\Models\SettingApp;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Yajra\DataTables\DataTables as DataTablesDataTables;

class InvoiceController extends Controller
{
    protected $settingApp;
    protected $invoices;
    protected $testOrders;
    protected $settingInvoice;
    protected $invoiceDetails;
    protected $setting;

    public function __construct(SettingApp $settingApp, Invoice $invoices, Setting $setting, TestOrder $testOrders, SettingInvoice $settingInvoice, InvoiceDetail $invoiceDetails)
    {
        $this->invoices = $invoices;
        $this->testOrders = $testOrders;
        $this->settingInvoice = $settingInvoice;
        $this->invoiceDetails = $invoiceDetails;
        $this->setting = $setting;
        $this->settingApp = $settingApp;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $invoices = $this->invoices->latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        $today = now()->format('Y-m-d'); // Récupérer la date d'aujourd'hui au format 'YYYY-MM-DD'->whereRaw('Date(updated_at) = ?', [now()->toDateString()])
        $closecashbox = CashboxDaily::where('status', 1)->orderBy('updated_at', 'desc')->first();
        if ($closecashbox) {
            $annuletotalToday = $this->invoices
                ->whereDate('updated_at', $today)
                ->where('paid', '=', 1)
                ->where(['status_invoice' => 1])
                ->where('updated_at', '>', $closecashbox->updated_at)->sum('total');
            $totalToday = $this->invoices
                ->whereDate('updated_at', $today)
                ->where('paid', '=', 1)
                ->where(['status_invoice' => 0])
                ->where('updated_at', '>', $closecashbox->updated_at)
                ->sum('total') - $annuletotalToday;
            $vente = $this->invoices
                ->where('status_invoice', '=', 0)
                ->count();
            $avoir = $this->invoices
                ->where('status_invoice', '=', 1)
                ->count();
        } else {
            $totalToday = 0;
            $vente = 0;
            $avoir = 0;
        }

        $month = $request->month; // Récupérez la valeur du mois depuis le formulaire
        $year = $request->year;   // Récupérez la valeur de l'année depuis le formulaire
        config(['app.name' => $setting->titre]);

        $list_years = TestOrder::select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        $sales = DB::table('invoices')
            ->selectRaw("
            SUM(total) AS total_sales
        ")->where('status_invoice', '0');

        if (isset($month) && isset($year)) {
            // Filtrer par mois et année si les deux sont spécifiés
            $sales = $sales->whereMonth('invoices.created_at', $month)
                ->whereYear('invoices.created_at', $year);
        } elseif (isset($year)) {
            $sales = $sales->whereYear('invoices.created_at', $year);
        }
        $sales = $sales->get();



        $credits = DB::table('invoices')
            ->selectRaw("
            SUM(total) AS total_credits
        ")
            ->where('status_invoice', '1');

        if (isset($month) && isset($year)) {
            // Filtrer par mois et année si les deux sont spécifiés
            $credits = $credits->whereMonth('invoices.created_at', $month)
                ->whereYear('invoices.created_at', $year);
        } elseif (isset($year)) {
            $credits = $credits->whereYear('invoices.created_at', $year);
        }

        $credits = $credits->get();
        $payments = DB::table('cashbox_adds')
            ->selectRaw("
            SUM(amount) AS total_payments
        ");

        if (isset($month) && isset($year)) {
            // Filtrer par mois et année si les deux sont spécifiés
            $payments = $payments->whereMonth('cashbox_adds.created_at', $month)
                ->whereYear('cashbox_adds.created_at', $year);
        } elseif (isset($year)) {
            $payments = $payments->whereYear('cashbox_adds.created_at', $year);
        }
        $payments = $payments->get();
        $salesByContracts = DB::table('invoices')
            ->select('contrat_id', DB::raw("SUM(total) as total_contracts"))
            ->groupBy('contrat_id');

        if (isset($month) && isset($year)) {
            // Filtrer par mois et année si les deux sont spécifiés
            $salesByContracts = $salesByContracts->whereMonth('invoices.created_at', $month)
                ->whereYear('invoices.created_at', $year);
        } elseif (isset($year)) {
            $salesByContracts = $salesByContracts->whereYear('invoices.created_at', $year);
        }
        $paymensalesByContracts = $salesByContracts->get();

        return view('invoices.index', compact('paymensalesByContracts', 'payments', 'credits', 'sales', 'list_years', 'month', 'year', 'invoices', 'totalToday', 'vente', 'avoir'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testOrders = $this->testOrders->all();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
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


        // $invoiceExist = $testOrder->invoice()->first();
        $invoiceExist = $this->invoices->where('test_order_id', $testOrder->id)->where('status_invoice', 0)->first();

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

    public function show(Request $request, $id)
    {
        $headerLogo = $this->settingApp->where('key', 'entete')->first();
        // Convertir les images en base64 pour DomPDF (méthode recommandée)
        $headerLogoPath = $headerLogo->value ? public_path('adminassets/images/' . $headerLogo->value) : '';

        $headerLogo = null;
        $signature = null;

        // Vérifier si les fichiers existent avant de les encoder
        if ($headerLogoPath) {
            $headerLogo = $headerLogoPath;
        }

        //Recupération du invoice
        $invoice = $this->invoices->findorfail($id);
        $refund = null;
        if ($invoice->status_invoice == 1) {
            $refund = RefundRequest::where('invoice_id', $invoice->reference)->first();
        }

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();

        // Génération du code QRCode
        $qrCode = Builder::create()
            ->writer(new PngWriter())
            ->data($invoice->code_normalise ?? "Centre ADECHINA Anatomie Pathologique")
            ->size(300)
            ->margin(10)
            ->build();

        $qrCodeBase = base64_encode($qrCode->getString());

        // Récupérer les données de la facture (à adapter selon votre base de données)
        $invoice = [
            'status_invoice' => $invoice->status_invoice,
            'date' => $invoice->created_at,
            'code' => $invoice?->status_invoice != 1 ? $invoice?->code : $refund?->code,
            'invoice_paid' => $invoice->paid,
            'type' => $invoice?->contrat?->name ?? $invoice?->order?->contrat?->name ?? '',
            'reference_code' => $refund?->invoice ? $refund?->invoice?->code : '',
            'mecef_code' => $invoice->code_normalise ?? '',
            'qr_code' => $qrCodeBase,
            'client' => [
                'name' => $invoice->client_name,
                'address' => $invoice?->client_address,
                'code' => $invoice?->patient ? $invoice?->patient?->code : '',
                'contact' => $invoice->telephone1 ? $invoice?->telephone1 . '/' . $invoice?->telephone2 : '',
            ],
            'exam_request' => $invoice?->order ? remove_hyphen($invoice?->order?->code) : '',
            'refund' => $refund,
            'items' => $this->formatInvoiceItems($invoice, $refund),
            'subtotal' => $invoice?->subtotal,
            'total_ttc' => $invoice?->total,
            'note' => 'Les résultats de vos analyses seront disponibles dans un délai de 3 semaines. Selon la complexité du cas, les résultats peuvent être disponibles plus tôt ou plus tard. Vous serez notifiés dès que les résultats seront prêts. Nous vous remercions de votre compréhension et de votre patience.',
            'footer' => $this->settingApp::where('key', 'report_footer')->first()->value ?? $setting->footer,
            'images' => [
                'header_logo' => $headerLogo,
                'signature' => Auth::user()->signature ? public_path('adminassets/images/'.Auth::user()->signature) : '',
            ]
        ];

        $pdf = PDF::loadView('invoices.show', compact('invoice'));

        // Configuration PDF
        $pdf->setPaper('A4', 'portrait');


        return $pdf->stream('invoices_show');
    }

    private function formatInvoiceItems($invoice, $refund)
    {
        $items = [];

        if ($refund) {
            $items[] = [
                'designation' => $refund ? ($refund->reason ? $refund->reason->description : '') : '',
                'quantity' => 1,
                'price' => $refund ? $refund->montant : 0,
                'discount' => 0.0,
                'total' => $refund ? $refund->montant : 0,
            ];
        } else {
            foreach ($invoice->details as $index => $item) {
                $items[] = [
                    'designation' => $item->test_name ?? '',
                    'quantity' => 1,
                    'price' => $item->price ?? 0,
                    'discount' => $item->discount ?? 0,
                    'total' => $item->total ?? 0,
                ];
            }
        }

        return $items;
    }

    public function getInvoiceforDatatable(Request $request)
    {

        $periode = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        $todayMonth = intval(date('m')); // récupère le mois actuel en tant qu'entier

        $periode = array_slice($periode, 0, $todayMonth); // garde uniquement les mois précédents la date d'aujourd'hui

        return FacadesDataTables::of($periode)->addIndexColumn()
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
                $monthIndex = array_search($periode, ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']);

                return $chiffre = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->where('paid', '1')->where(['status_invoice' => 1])->sum('total');
            })
            ->addColumn('chiffres', function ($periode) {

                $monthIndex = array_search($periode, ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']);

                $chiffre = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->where(['status_invoice' => 0])->where('paid', '1')->sum('total');
                return $chiffre ? $chiffre : 'Néant';
            })
            ->addColumn('encaissements', function ($periode) {
                $monthIndex = array_search($periode, ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre']);

                $vente = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->where(['status_invoice' => 0])->where('paid', '1')->sum('total');
                $avoir = $this->invoices->whereMonth('updated_at', $monthIndex + 1)->where(['status_invoice' => 1])->where('paid', '1')->sum('total');
                $resultat = $vente - $avoir;
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

    public function getInvoiceIndexforDatable(Request $request)
    {
        $data = $this->invoices->latest();
        return DataTablesDataTables::of($data)->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                return $data->date;
            })

            ->setRowClass(function ($data) use ($request) {
                if ($data->status_invoice == 1) {
                    return 'table-danger';
                }
            })

            ->addColumn('demande', function ($data) {

                $code = 0;
                if ($data->test_order_id) {
                    $code = getTestOrderData($data->test_order_id)->code;
                } else {
                    $code = $data->code;
                }
                return $code;

                // return $data->test_order_id ? getTestOrderData($data->test_order_id)->code :'';
            })
            ->addColumn('patient', function ($data) {

                $patient = "";
                if ($data->test_order_id) {
                    $patient = getTestOrderData($data->test_order_id)->patient->firstname . ' ' . getTestOrderData($data->test_order_id)->patient->lastname;
                } else {
                    $patient = $data->client_name;
                }
                return $patient;

                // return $data->test_order_id?
                // getTestOrderData($data->test_order_id)->patient->firstname.'
                // '.getTestOrderData($data->test_order_id)->patient->lastname :'';
            })
            ->addColumn('total', function ($data) {
                return $data->total;
            })
            // ->addColumn('remise', function ($data) {
            //     return $data->discount?$data->discount:'0,0';
            // })

            // ->addColumn('type', function ($data) {
            //     $badge  =$data->status_invoice == 1 ? "Avoir" : "Vente";
            //     return $badge;
            // })
            ->addColumn('code', function ($data) {

                // $inputCode = '<input type="text" name="code" id="code" class="form-control" style="margin-right: 20px;"/>';
                return $data->codeMecef ? $data->codeMecef : $data->code_normalise;
                // return $inputCode;


            })

            ->addColumn('type', function ($data) {
                $selector = '
                    <div class="mb-3">
                        <select class="form-select select2" data-toggle="select2" name="payment" value="' . $data->payment . '" id="payment" required>
                            <option ' . $data->payment . ' == ' . 'ESPECES' . ' ? ' . 'selected' . ' : ' . '' . '
                                value="ESPECES">ESPECES</option>
                            <option ' . $data->payment . ' == ' . 'CHEQUES' . ' ? ' . 'selected' . ' : ' . '' . '
                                value="CHEQUES">CHEQUES</option>
                            <option ' . $data->payment . ' == ' . 'MOBILEMONEY' . ' ? ' . 'selected' . ' : ' . '' . '
                                value="MOBILEMONEY">MOBILE MONEY</option>
                            <option ' . $data->payment . ' == ' . 'CARTEBANCAIRE' . ' ? ' . 'selected' . ' : ' . '' . '
                                value="CARTEBANCAIRE">CARTE BANQUAIRE</option>
                            <option ' . $data->payment . ' == ' . 'VIREMENT' . ' ? ' . 'selected' . ' : ' . '' . '
                                value="VIREMENT">VIREMENT</option>
                            <option ' . $data->payment . ' == ' . 'CREDIT' . ' ? ' . 'selected' . ' : ' . '' . '
                                value="CREDIT">CREDIT</option>
                            <option ' . $data->payment . ' == ' . 'AUTRE' . ' ? ' . 'selected' . ' : ' . '' . '
                            value="AUTRE">AUTRE</option>
                        </select>
                    </div>
                ';

                // return $selector;
                return  $data->payment;
            })
            ->addColumn('status', function ($data) {

                switch ($data->paid == 1) {
                    case 1:
                        $btn = '<span class="badge bg-success rounded-pill p-1"> Payé </span>';
                        break;

                    default:
                        $btn = '<span class="badge bg-warning rounded-pill p-1"> En attente </span>';
                        break;
                }
                // if($data->paid == 0)
                // {
                //     $btn = '<span class="badge bg-warning rounded-pill p-1"> En attente </span>';
                // }elseif($data->paid == 1){
                //     $btn = '<span class="badge bg-warning rounded-pill p-1"> En attente </span>';
                // }

                return $btn;
            })

            ->addColumn('action', function ($data) {
                if (getTestOrderData($data->test_order_id)) {
                    // $btnVoir = '<a type="button" href="' . route('details_test_order.index', getTestOrderData($data->test_order_id)->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>';
                    // $btnVoir = '<button type="button"  class="btn btn-primary invoice-btn" title="Voir les détails" data-test-order-id="' . $data->id . '"><i class="mdi mdi-eye"></i></button>';
                    // $btnVoir = '<button type="button"  class="btn btn-primary" title="Voir les détails" onclick="alert('.getTestOrderData($data->test_order_id)->id .')"><i class="mdi mdi-eye"></i></button>';
                    // $testOrderId = getTestOrderData($data->test_order_id)->id;
                    // $btnVoir = '<button type="button" class="btn btn-primary" title="Voir les détails" onclick="afficherDetails(' . $testOrderId . ')"><i class="mdi mdi-eye"></i></button>';
                } else {
                    $btnVoir = '';
                }
                $btnVoir = '';

                $btnInvoice = ' <a type="button" href="' . route('invoice.show', $data->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';

                return $btnVoir . $btnInvoice;
            })
            ->filter(function ($query) use ($request) {

                if (!empty($request->get('cas_status'))) {
                    if ($request->get('cas_status') == 1) {
                        $query->where('paid', 1);
                    } else {
                        $query;
                    }
                } else {
                    if ($request->get('cas_status') == 0) {
                        $query->where('paid', 0);
                    } else {
                        $query->where('paid', 1);
                    }
                }



                if (!empty($request->get('status_invoice'))) {
                    if (!$request->get('status_invoice')) {
                        $query->where('status_invoice', 0);
                    } else {
                        $query->where('status_invoice', 1);
                    }
                }


                if (!empty($request->get('contenu'))) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $query->where('code', 'like', '%' . $request->get('contenu') . '%');
                    })
                        ->orwhereHas('patient', function ($query) use ($request) {
                            $query->where('firstname', 'like', '%' . $request->get('contenu') . '%')
                                ->orwhere('lastname', 'like', '%' . $request->get('contenu') . '%');
                        })
                        ->orwhereHas('contrat', function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->get('contenu') . '%');
                        })

                    ;
                }


                if (!empty($request->get('dateBegin'))) {
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                    $query->whereDate('created_at', '>=', $newDate);
                }


                if (!empty($request->get('dateEnd'))) {
                    $query->whereDate('created_at', '<=', $request->get('dateEnd'));
                }
            })
            ->rawColumns(['demande', 'total', 'remise', 'patient', 'type', 'status', 'action'])
            ->make(true);
    }


    public function getInvoice($id)
    {
        $invoice = $this->invoices->find($id);
        return response()->json($invoice);
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

    public function searchInvoice(Request $request)
    {
        $start = Carbon::createFromFormat('Y-m-d', $request->starting_date);
        $end = Carbon::createFromFormat('Y-m-d', $request->ending_date);

        $vente = $this->invoices->whereDate('updated_at', '>=', $start)->whereDate('updated_at', '<=', $end)->where(['status_invoice' => 0])->where('paid', '1')->sum('total');
        $avoir = $this->invoices->whereDate('updated_at', '>=', $start)->whereDate('updated_at', '<=', $end)->where(['status_invoice' => 1])->where('paid', '1')->sum('total');
        $total = $vente - $avoir;
        $facture = $this->invoices->whereDate('updated_at', '>=', $start)->whereDate('updated_at', '<=', $end)->sum('total');


        // $invoice = Invoice::where('paid',1)->where('updated_at','>=',$start)->where('updated_at','<=',$end)->get();
        return response()->json(['ca' => $vente, 'avoir' => $avoir, 'facture' => $facture, 'encaissement' => $total]);
    }

    function print($id)
    {
        $invoice = $this->invoices->findorfail($id);
        $settingInvoice = $this->settingInvoice->find(1);
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();

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

            $ifu = SettingApp::where('key', 'ifu')->first();
            $whatsapp_number = SettingApp::where('key', 'whatsapp_number')->first();
            $rccm = SettingApp::where('key', 'rccm')->first();

            return redirect()->route('invoice.show', [$invoice->id])->with('success', " Facture crée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement. Veuillez réessayer svp ! " . $ex->getMessage());
        }
    }

    // Met à jour le statut paid pour le payement
    public function updateStatus(Request $request, $id)
    {
        $invoice = $this->invoices->findorfail($id);
        $settingInvoice = $this->settingInvoice->find(1);

        if ($invoice->paid == 1) {
            return redirect()->back()->with('success', "Cette facture a déjà été payé ! ");
        } else {

            // Si facture normaliser est activé
            if ($settingInvoice->status == 1) {
                $invoice->fill([
                    "paid" => '1',
                    "payment" => $request->payment
                ])->save();

                $cash = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type', 'vente')->first();
                $cash->current_balance += $invoice->total;
                $cash->save();

                CashboxAdd::create([
                    'cashbox_id' => 2,
                    'date' => Carbon::now(),
                    'amount' => $invoice->total,
                    'invoice_id' => $invoice->id,
                    'user_id' => Auth::user()->id
                ]);

                if ($invoice->contrat) {
                    if ($invoice->contrat->invoice_unique == 1) {
                        $invoice->contrat->is_close = 1;
                        $invoice->contrat->save();
                    }
                }

                if ($invoice->test_order_id != null) {
                    return response()->json(invoiceNormeTest($invoice->test_order_id));
                }
            } else {






                $invoice->fill([
                    "paid" => '1',
                    'payment' => $request->payment,
                    "code_normalise" => $request->code,
                ])->save();

                if ($invoice->status_invoice != 1) {

                    $cash = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type', 'vente')->first();
                    $cash->current_balance += $invoice->total;
                    $cash->save();

                    CashboxAdd::create([
                        'cashbox_id' => 2,
                        'date' => Carbon::now(),
                        'amount' => $invoice->total,
                        'invoice_id' => $invoice->id,
                        'user_id' => Auth::user()->id
                    ]);
                } else {

                    $cash = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type', 'depense')->first();
                    $cash->current_balance -= $invoice->total;
                    $cash->save();
                    CashboxAdd::create([
                        'cashbox_id' => 1,
                        'date' => Carbon::now(),
                        'amount' => $invoice->total,
                        'user_id' => Auth::user()->id
                    ]);
                }

                if ($invoice->contrat) {
                    if ($invoice->contrat->invoice_unique == 1) {
                        $invoice->contrat->is_close = 1;
                        $invoice->contrat->save();
                    }
                }

                return response()->json(['code' => $request->code]);
            }
        }
    }

    public function checkCode(Request $request)
    {
        $invoice = $this->invoices->where('code_normalise', '=', $request->code)->orwhere('codeMecef', '=', $request->code)->first();
        if (!empty($invoice)) {
            return response()->json(['code' => 1]);
        } else {
            return response()->json(['code' => 0]);
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

        // return response()->json(['status' => 200, 'type' => "confirm",'response'=>$request->uid]);
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
