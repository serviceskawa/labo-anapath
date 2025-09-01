<?php

namespace App\Http\Controllers;

use App\Events\AssignedReviewer;
use App\Http\Requests\TagRequest;
use App\Models\AppelByReport;
use App\Models\Cashbox;
use App\Models\Report;

//use App\Models\Contrat;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\LogReport;
use App\Models\Setting;
use App\Models\SettingApp;
use GuzzleHttp\Client;
//use App\Helpers\herpers;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Spipu\Html2Pdf\Html2Pdf;
// require _DIR_.'/vendor/autoload.php';
use App\Models\SettingReportTemplate;
use App\Models\Tag;
use App\Models\TestOrder;
use App\Models\TestOrderAssignment;
use App\Models\TitleReport;
use App\Models\TypeOrder;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Yajra\DataTables\Facades\DataTables;
use TCPDF;

class ReportController extends Controller
{
    protected $report;
    protected $doctor;
    protected $logReport;
    protected $settingReportTemplate;
    protected $titleReport;
    protected $setting;
    protected $user;
    protected $tag;
    protected $typeOrder;
    protected $testOrder;

    /**
     * ReportController constructor.
     * Instanciate Report, Doctor and LogReport classes
     */
    public function __construct(TestOrder $testOrder, Report $report, Doctor $doctor, User $user, LogReport $logReport, TitleReport $titleReport, SettingReportTemplate $settingReportTemplate, Setting $setting, Tag $tag, TypeOrder $typeOrder)
    {
        $this->middleware('auth');
        $this->report = $report;
        $this->doctor = $doctor;
        $this->logReport = $logReport;
        $this->titleReport = $titleReport;
        $this->settingReportTemplate = $settingReportTemplate;
        $this->setting = $setting;
        $this->user = $user;
        $this->tag = $tag;
        $this->typeOrder = $typeOrder;
        $this->testOrder = $testOrder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!getOnlineUser()->can('view-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $reports = $this->report->orderBy('created_at', 'DESC')->get();
        // $doctors = $this->doctor->all();
        $tags = $this->tag->all();
        $user = Auth::user();

        // Code debut pour le filtre
        $month = $request->month; // Récupérez la valeur du mois depuis le formulaire
        $year = $request->year;   // Récupérez la valeur de l'année depuis le formulaire
        $doctor = intval($request->doctor);   // Récupérez la valeur de l'année depuis le formulaire

        $list_years = Report::select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        $testOrderIds = TestOrderAssignment::where('user_id', $doctor)
            ->with('details:test_order_id,test_order_assignment_id') // Charger uniquement les champs nécessaires
            ->get()
            ->pluck('details.*.test_order_id')
            ->flatten()
            ->unique()
            ->values();

        // Filtrer les enregistrements de la table reports en fonction des test_order_id, du status et de la date
        $report_req = Report::whereIn('test_order_id', $testOrderIds)
            ->where('status', 1)
            ->whereMonth('signature_date', intval($month))
            ->whereYear('signature_date', intval($year))
            ->get();

        //Somme du total du chiffre d'affaire generer par le medecin
        $totalSum = 0;
        $totalSum = TestOrder::whereIn('id', $testOrderIds)
            ->whereHas('report', function ($query) use ($month, $year) {
                $query->where('status', 1)
                    ->whereMonth('signature_date', intval($month))
                    ->whereYear('signature_date', intval($year));
            })
            ->whereHas('contrat', function($query){
                $query->where('name','ORDINAIRE')
                ->where('type','ORDINAIRE')
                ->where('status','ACTIF');
            })
            ->sum('total');

         $totalSum1 = TestOrder::whereIn('id', $testOrderIds)
            ->whereHas('report', function ($query) use ($month, $year) {
                $query->where('status', 1)
                    ->whereMonth('signature_date', intval($month))
                    ->whereYear('signature_date', intval($year));
            })
            ->whereHas('contrat', function($query){
                $query->where('name','ORDINAIRE')
                ->where('type','ORDINAIRE')
                ->where('status','ACTIF');
            })
            ->get();

        $report_nbres = $report_req->count();
        // Initialiser les compteurs
        $withinDeadlineCount = 0;
        $beyondDeadlineCount = 0;

        // Effectuer la requête avec des jointures et des filtres
        $results = DB::table('reports')
            ->join('test_order_assignment_details', 'reports.test_order_id', '=', 'test_order_assignment_details.test_order_id')
            ->join('test_order_assignments', 'test_order_assignment_details.test_order_assignment_id', '=', 'test_order_assignments.id')
            ->where('test_order_assignments.user_id', $doctor)
            ->where('reports.status', 1)
            ->whereMonth('reports.signature_date', $month)
            ->whereYear('reports.signature_date', $year)
            ->select(
                DB::raw('SUM(CASE WHEN DATEDIFF(reports.signature_date, test_order_assignments.date) <= 11 THEN 1 ELSE 0 END) as within_deadline'),
                DB::raw('SUM(CASE WHEN DATEDIFF(reports.signature_date, test_order_assignments.date) > 11 THEN 1 ELSE 0 END) as beyond_deadline')
            )
            ->first();

        $in_deadline = intval($results->within_deadline);
        $over_deadline = intval($results->beyond_deadline);

        // Calcul de la somme totale
        $total = $in_deadline + $over_deadline;

        // Calcul des pourcentages
        $percentageIn_Deadline = $in_deadline == 0 ? 0 : number_format(($in_deadline / $total) * 100, 1);
        $percentageOver_Deadline = $over_deadline == 0 ? 0 : number_format(($over_deadline / $total) * 100, 1);

        $commission = User::find($doctor)?->commission ?? 0;
        return view('reports.index', compact('totalSum', 'commission', 'doctor', 'percentageOver_Deadline', 'percentageIn_Deadline', 'report_nbres', 'list_years', 'year', 'month', 'tags', 'reports'));
    }

    public function storeTags(TagRequest $request)
    {
        $data = [
            'name' => $request->name,
        ];

        $exist = $this->tag->where('id', $request->name)->first();
        try {
            if ($exist === null) {
                $tag = $this->tag->create($data);
                $status = "created";
            } else {
                $tag = [];
                $status = "exist";
            }

            return response()->json(["tag" => $tag, "status" => $status], 200);
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }


    public function indexsuivi(Request $request)
    {
        if (!getOnlineUser()->can('view-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $reports = $this->report->orderBy('created_at', 'DESC')->get();
        $doctors = $this->doctor->all();
        $user = Auth::user();
        $types_orders = $this->typeOrder->all();

        $month = $request->month; // Récupérez la valeur du mois depuis le formulaire
        $year = $request->year;   // Récupérez la valeur de l'année depuis le formulaire

        $examens = DB::table('type_orders as tos')
            ->join('test_orders as tor', 'tos.id', '=', 'tor.type_order_id')
            ->select(
                DB::raw("SUM(CASE WHEN tos.title = 'Histologie' THEN 1 ELSE 0 END) AS histologie"),
                DB::raw("SUM(CASE WHEN tos.title = 'Immuno Externe' THEN 1 ELSE 0 END) AS immuno_externe"),
                DB::raw("SUM(CASE WHEN tos.title = 'Immuno Interne' THEN 1 ELSE 0 END) AS immuno_interne"),
                DB::raw("SUM(CASE WHEN tos.title = 'Cytologie' THEN 1 ELSE 0 END) AS cytologie"),
                DB::raw("COUNT(tor.id) AS total_general")
            )
            ->where('tor.status', 1);

        if (isset($month) && isset($year)) {
            // Filtrer par mois et année si les deux sont spécifiés
            $examens = $examens->whereMonth('tor.created_at', $month)
                ->whereYear('tor.created_at', $year);
        } elseif (isset($year)) {
            $patient_called = $examens->whereYear('tor.created_at', $year);
        }
        $examens = $examens->get();


        $rapports = DB::table('reports as rep')
            ->join('test_orders as tor', 'tor.id', '=', 'rep.test_order_id')
            ->leftJoin('test_order_assignment_details as toad', 'toad.test_order_id', '=', 'rep.test_order_id')
            ->selectRaw("
            SUM(CASE WHEN rep.status = '0' THEN 1 ELSE 0 END) AS attente,
            SUM(CASE WHEN rep.status = '1' THEN 1 ELSE 0 END) AS termine,
            SUM(CASE WHEN toad.test_order_id IS NOT NULL THEN 1 ELSE 0 END) AS affecte
        ");

        if (isset($month) && isset($year)) {
            $rapports = $rapports->whereMonth('tor.created_at', $month)
                ->whereYear('tor.created_at', $year);
        } elseif (isset($year)) {
            $patient_called = $rapports->whereYear('tor.created_at', $year);
        }

        $rapports = $rapports->get();

        $macros = DB::table('test_orders as tor')
            ->rightJoin('test_pathology_macros as tpm', 'tpm.id_test_pathology_order', '=', 'tor.id')
            ->selectRaw("
            SUM(CASE WHEN tpm.id_test_pathology_order = tor.id THEN 1 ELSE 0 END) AS pathology
        ");

        if (isset($month) && isset($year)) {
            $macros = $macros->whereMonth('tor.created_at', $month)
                ->whereYear('tor.created_at', $year);
        } elseif (isset($year)) {
            $patient_called = $macros->whereYear('tor.created_at', $year);
        }


        $macros = $macros->get();

        $patient_called = DB::table('reports')
            ->selectRaw("
            SUM(CASE WHEN reports.is_called = 1 THEN 1 ELSE 0 END) AS called,
            SUM(CASE WHEN reports.is_called = 0 THEN 1 ELSE 0 END) AS not_called,
            SUM(CASE WHEN reports.is_delivered = 1 THEN 1 ELSE 0 END) AS deliver,
            SUM(CASE WHEN reports.is_delivered = 0 THEN 1 ELSE 0 END) AS not_deliver
        ");

        if (isset($month) && isset($year)) {
            $patient_called = $patient_called->whereMonth('reports.created_at', $month)
                ->whereYear('reports.created_at', $year);
        } elseif (isset($year)) {
            $patient_called = $patient_called->whereYear('reports.created_at', $year);
        }


        $patient_called = $patient_called->get();

        $list_years = TestOrder::select(DB::raw('YEAR(created_at) as year'))
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        return view('reports.suivi.index', compact('list_years', 'month', 'year', 'patient_called', 'macros', 'rapports', 'examens', 'reports', 'doctors', 'types_orders'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $doctor_signataire1 = $request->doctor_signataire1;
        $doctor_signataire2 = $request->doctor_signataire2;
        $doctor_signataire3 = $request->doctor_signataire3;
        $revew_by = null;
        if (!empty($request->reviewed_by_user_id)) {
            $revew_by = $request->reviewed_by_user_id;
        }

        $user = Auth::user();

        $report = $this->report->findorfail($request->report_id);
        if (!empty($request->reviewed_by_user_id) && $report->reviewed_by_user_id != $request->reviewed_by_user_id) {
            $user = User::find($request->reviewed_by_user_id);
            $data = [
                'user_name' => $user->fullname(),
                'report_title' => $request->title,
                'report_test_order' => $report->order->code
            ];

            event(new AssignedReviewer($request->reviewed_by_user_id, $data));
        }

        if ($request->status == 1) {
            $report
                ->fill([
                    'delivery_date' => now(),
                    'description' => $request->content,
                    'comment' => $request->comment,
                    'comment_sup' => $request->comment_sup,
                    'description_micro' => $request->content_micro,
                    'signatory1' => $doctor_signataire1,
                    'signatory2' => $doctor_signataire2,
                    'signatory3' => $doctor_signataire3,
                    'reviewed_by_user_id' => $revew_by,
                    'status' => $request->status == '1' ? '1' : '0',
                    'title' => $request->title,
                    'description_supplementaire' => $request->description_supplementaire != '' ? $request->description_supplementaire : '',
                    'description_supplementaire_micro' => $request->description_supplementaire_micro != '' ? $request->description_supplementaire_micro : '',
                ])
                ->save();
        } elseif ($request->status == 0) {
            $report
                ->fill([
                    'description' => $request->content,
                    'comment' => $request->comment,
                    'comment_sup' => $request->comment_sup,
                    'description_micro' => $request->content_micro,
                    'signatory1' => $doctor_signataire1,
                    'signatory2' => $doctor_signataire2,
                    'signatory3' => $doctor_signataire3,
                    'reviewed_by_user_id' => $revew_by,
                    'status' => $request->status == '1' ? '1' : '0',
                    'title' => $request->title,
                    'description_supplementaire' => $request->description_supplementaire != '' ? $request->description_supplementaire : '',
                    'description_supplementaire_micro' => $request->description_supplementaire_micro != '' ? $request->description_supplementaire_micro : '',
                ])
                ->save();
        }

        $report->order->assigned_to_user_id =  $request->doctor_signataire1;
        $report->order->save();
        if ($report->status == 1) {
            $report->signature_date = Carbon::now();
            $report->save();
        }

        try {

            $report->tags()->sync([]);
            $report->tags()->attach($request->tags);

            return redirect()->route('report.show', $report->id)->with('success', " Utilisateur mis à jour ! ");
        } catch (\Throwable $th) {
            return redirect()->route('report.show', $report->id)->with('error', "Échec de l'enregistrement ! " . $th->getMessage());
        }

        $log = new LogReport();
        $log->operation = 'Mettre à jour ';
        $log->report_id = $request->report_id;
        $log->user_id = $user->id;
        $log->save();

        return redirect()->back()->with('success', '   Examen mis à jour ! ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!getOnlineUser()->can('view-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $report = $this->report->findorfail($id);
        $test_order = $this->testOrder->findorfail($report->test_order_id);

        $templates = $this->settingReportTemplate->all();
        $titles = $this->titleReport->all();
        $logs = $this->logReport
            ->where('report_id', 'like', $report->id)
            ->latest()
            ->get();
        $setting = $this->setting->find(1);
        $cashbox = Cashbox::find(2);
        config(['app.name' => $setting->titre]);

        $tags = $this->tag->all();
        return view('reports.show', compact('test_order', 'report', 'setting', 'templates', 'titles', 'logs', 'cashbox', 'tags'));
    }

    public function pdf($id)
    {
        // dd($id);
        if (!getOnlineUser()->can('edit-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = $this->report->find($id);
        $setting = $this->setting->find(1);
        $text = $report->order ? $report->order->code : '';
        $user = Auth::user();

        // Chemin de sauvegarde
        $path = 'settings/app/';
        $qrPng = $report->code . '_qrcode.png';

        // Vérifier et créer le chemin s'il n'existe pas
        if (!Storage::exists('public/' . $path)) {
            Storage::makeDirectory('public/' . $path);
        }

        $qrCode = new QrCode($text);
        $qrCode->setSize(300);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrPng = $report->code . '_qrcode.png';

        // Enregistrer le fichier
        $result->saveToFile(storage_path('app/public/' . $path . $qrPng));

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        // Obtenir le chemin public du fichier
        $fileUrl = Storage::url('public/' . $path . $qrPng);

        if ($report->signatory1 != 0) {
            $signatory1 = $this->user->findorfail($report->signatory1);
        }

        if ($report->reviewed_by_user_id != 0) {
            $reviewed_by_user = $this->user->findorfail($report->reviewed_by_user_id);
        }

        if ($report->signatory2 != 0) {
            $signatory2 = $this->user->findorfail($report->signatory2);
        }

        if ($report->signatory3 != 0) {
            $signatory3 = $this->user->findorfail($report->signatory3);
        }
        $year_month = '';
        if ($report->order->patient->year_or_month != 1) {
            $year_month = 'mois';
        } else {
            $year_month = 'ans';
        }

        $data = [
            'fileUrl' => $fileUrl,
            'code' => $report->code,
            'test_order_code' => $report->order->code,
            'current_date' => utf8_encode(strftime('%d/%m/%Y')),
            'signature_date' => date('d/m/Y', strtotime($report->signature_date)),
            'prelevement_date' => $report->order ? date('d/m/Y', strtotime($report->order->prelevement_date)) : '',
            'test_affiliate' => $report->order ? $report->order->test_affiliate : '',
            'qrcode' => $dataUri,
            'title' => $report->title,
            'content' => $report->description,
            'content_micro' => $report->description_micro,
            'content_supplementaire' => $report->description_supplementaire != '' ? $report->description_supplementaire : '',
            'content_supplementaire_micro' => $report->description_supplementaire_micro != '' ? $report->description_supplementaire_micro : '',
            'signator' => $report->signateur  ? $report->signateur->lastname . ' ' . $report->signateur->firstname : '',
            'signature1' => $report->signateur ? $report->signateur->signature : '',
            'signator1' => $report->signatory1 ? $report->signatory1 : null,
            'signatory2' => $report->signatory2 != 0 ? $signatory2->lastname . ' ' . $signatory2->firstname : '',
            'signature2' => $report->signatory2 != 0 ? $signatory2->signature : '',
            'signatory3' => $report->signatory3 != 0 ? $signatory3->lastname . ' ' . $signatory3->firstname : '',
            'signature3' => $report->signatory3 != 0 ? $signatory3->signature : '',
            'patient_firstname' => $report->order->patient->firstname,
            'patient_lastname' => $report->order->patient->lastname,
            'patient_age' => $report->order->patient->age,
            'patient_year_or_month' => $year_month,
            'patient_genre' => $report->order->patient->genre,
            'status' => $report->status,
            'revew_by' => $report->reviewed_by_user_id != 0 ? $reviewed_by_user->lastname . ' ' . $reviewed_by_user->firstname : '',
            'revew_by_signature' => $report->reviewed_by_user_id != 0 ? $report->user->signature : 'Admin_Admin.png',
            'report_review_title' => SettingApp::where('key', 'report_review_title')->first()->value,
            'entete' => SettingApp::where('key', 'entete')->first()->value,
            'footer' => SettingApp::where('key', 'report_footer')->first()->value,
            'hospital_name' => $report->order ? $report->order->hospital->name : '',
            'doctor_name' => $report->order ? $report->order->doctor->name : '',
            'created_at' => date_format($report->created_at, 'd/m/Y'),
            'date' => date('d/m/Y'),
        ];

        try {
            $impression_file_name = SettingApp::where('key', 'impression_file_name')->first();
            $log = new LogReport();
            $log->operation = 'Imprimer';
            $log->report_id = $id;
            $log->user_id = $user->id;
            $log->save();

            $pdf = PDF::loadView('pdf.canva_' . $impression_file_name->value, compact('data'))
                ->setPaper('a4', 'portrait')
                ->setWarnings(false);

            // Enregistrer directement dans le dossier storage/app/public
            $filename = "documents/" . time() . ".pdf";
            Storage::disk('public')->put($filename, $pdf->output());

            // Lancement du téléchargement du fichier PDF
            return $pdf->stream($report->code . ".pdf");
            // } catch (Html2PdfException $e) {
        } catch (Exception $e) {
            Log::info($e->getMessage());
            $message = 'Erreur lors de la génération du PDF';
            // Message d'erreur explicite pour l'utilisateur
            return redirect()->back()->with('error', $message);
        }
    }

    public function getTemplate(Request $request)
    {
        $template = $this->settingReportTemplate->findorfail($request->id);

        return response()->json($template, 200);
    }

    // Met à jour le statut livré
    public function updateDeliverStatus($reportId)
    {
        // if (!getOnlineUser()->can('edit-reports')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $report = $this->report->findorfail($reportId);

        if (empty($report)) {
            return response()->json(['error' => "Ce compte rendu n'existe pas. Veuillez ressayer!"]);
        }


        $report
            ->fill([
                'is_deliver' => 1,
            ])
            ->save();
        // $this->pdf($reportId)';

        return response()->json(['report' => $report]);
    }
    // Lancer un appel ou envoyer un sms
    public function callOrSendSms($reportId)
    {
        if (!getOnlineUser()->can('edit-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = $this->report->findorfail($reportId);

        if (empty($report)) {
            return redirect()
                ->back()
                ->with('error', "Ce compte rendu n'existe pas. Veuillez ressayer ! ");
        }

        $beging = Carbon::createFromTime(8, 0, 0);
        $end = Carbon::createFromTime(18, 0, 0);
        $now = Carbon::now();

        // dd($report->order);
        if ($report->order->option) {
            $this->sendSms($report);
        } else {
            if ($now >= $beging && $now <= $end) {
                $this->callUser($report);
                // dd('je peux envoyer');
            }
        }
        // dd($report);
        return redirect()->back()->with('success', "Effectué avec succès ! ");
    }

    public function getReportsforDatatable(Request $request)
    {
        $data = $this->report->with('order')->latest();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('code', function ($data) {
                if ($data->order) {
                    return $data->order->code;
                } else {
                    return '';
                }
            })
            ->addColumn('codepatient', function ($data) {
                return $data->order->patient->code;
            })
            ->addColumn('patient', function ($data) {
                return $data->order->patient->firstname . ' ' . $data->order->patient->lastname;
            })
            ->addColumn('telephone', function ($data) {
                return $data->order->patient->telephone1;
            })
            ->addColumn('created_at', function ($data) {
                return \Carbon\Carbon::parse($data->created_at)->format('d/m/Y');
            })
            ->addColumn('status', function ($data) {
                if ($data->status == 1) {
                    return 'Valider';
                } else {
                    return 'Attente';
                }
            })
            ->addColumn('action', function ($data) {
                $btnVoir = '<a type="button" href="' . route('report.show', $data->id) . '"class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>';
                if ($data->order) {
                    $btnReport = ' <a type="button" href="' . route('details_test_order.index', $data->order->id) . '" class="btn btn-warning" title="Demande ' . $data->order->code . '"><i class="uil-file-medical"></i> </a>';
                } else {
                    $btnReport = ' ';
                }

                return $btnVoir . $btnReport;
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request->get('statusquery'))) {
                    if ($request->get('statusquery') == 1) {
                        $query->where('status', 1);
                    } else {
                        $query->where('status', 0);
                    }
                }

                if (!empty($request->get('contenu'))) {
                    $query
                        ->where('code', 'like', '%' . $request->get('contenu') . '%')
                        ->orwhereHas('order', function ($query) use ($request) {
                            $query->where('code', 'like', '%' . $request->get('contenu') . '%');
                        })
                        ->orwhere('description', 'like', '%' . $request->get('contenu') . '%')
                        ->orwhereHas('order', function ($query) use ($request) {
                            $query->whereHas('patient', function ($query) use ($request) {
                                $query->where('firstname', 'like', '%' . $request->get('contenu') . '%')
                                    ->orwhere('code', 'like', '%' . $request->get('contenu') . '%')
                                    ->orwhere('lastname', 'like', '%' . $request->get('contenu') . '%');
                            });
                        });
                }

                if (!empty($request->get('dateBegin'))) {
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                    $query->whereDate('created_at', '>', $newDate);
                }

                if (!empty($request->get('dateEnd'))) {
                    $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                    $query->whereDate('created_at', '<', $newDate);
                }
            })
            ->make(true);
    }


    public function UpdateLivrePatient(Request $request)
    {
        $updatereport = Report::find($request->data_id_report);

        $updatereport->is_delivered = 1;
        $updatereport->delivery_date = now();

        $updatereport->save();

        return response()->json([
            "message" => "Operation réussie avec succès!",
            'TestId' => $updatereport
        ], 200);
    }


    public function UpdateInformePatient(Request $request)
    {
        $updatereport = Report::find($request->data_id_report);

        $updatereport->is_called = 1;
        $updatereport->call_date = now();

        $updatereport->save();

        return response()->json([
            "message" => "Operation réussie avec succès!",
            'TestId' => $updatereport
        ], 200);
    }


    public function getTestOrdersforDatatableSuivi(Request $request)
    {
        $data = $this->report->with(['order.assignmentTestOrder'])->latest();
        return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('date', function ($data) {
                if ($data->order) {
                    return dateFormat($data->order->created_at);
                } else {
                    return '';
                }
            })

            ->editColumn('code', function ($data) {
                if ($data->order) {
                    return  view("reports.suivi.code", ['data' => $data]);
                } else {
                    return '';
                }
            })

            ->addColumn('macro', function ($data) {
                return  view("reports.suivi.btnmacro", ['data' => $data]);
            })

            ->addColumn('report', function ($data) {
                return  view("reports.suivi.btnreport", ['data' => $data]);
            })

            ->addColumn('call', function ($data) {
                return  view("reports.suivi.btninforme", ['data' => $data]);
            })

            ->addColumn('delivery', function ($data) {
                return  view("reports.suivi.btndelivery", ['data' => $data]);
            })

            ->filter(function ($query) use ($request) {
                if (!empty($request->get('type_examen'))) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $query->where('type_order_id', $request->type_examen);
                    });
                }

                if (!empty($request->get('cas_status'))) {
                    if ($request->get('cas_status') == 'Retard') {
                        $threeWeeksAgo = now()->subDays(21);
                        $query->where('created_at', '<=', $threeWeeksAgo)
                            ->where('status', 0);
                    }

                    $query->whereHas('order', function ($query) use ($request) {
                        if ($request->cas_status == 'Urgent') {
                            $query->where('is_urgent', 0);
                        }
                    });
                }

                if (!empty($request->get('statusquery'))) {
                    if ($request->get('statusquery') == 1) {
                        $query->where('is_delivered', 1);
                    } elseif ($request->get('statusquery') == 2) {
                        $query->where('is_called', 1);
                    } elseif ($request->get('statusquery') == 3) {
                        $query->where('status', 0);
                    } elseif ($request->get('statusquery') == 4) {
                        $query->where('status', 1);
                    } elseif ($request->get('statusquery') == 5) {
                        $query->where('status', 1)->where('is_delivered', 0);
                    }
                }

                if (!empty($request->get('contenu'))) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $searchTerm = '%' . $request->get('contenu') . '%';

                        $query->where('code', 'like', $searchTerm)
                            ->orWhereHas('patient', function ($query) use ($searchTerm) {
                                $query->where('firstname', 'like', $searchTerm)
                                    ->orWhere('lastname', 'like', $searchTerm);
                            })
                            ->orWhereHas('doctor', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm);
                            });
                    });
                }

                if (!empty($request->get('dateBegin'))) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $newDate = Carbon::createFromFormat('Y-m-d', $request->get('dateBegin'));
                        $query->whereDate('created_at', '>=', $newDate);
                    });
                }

                if (!empty($request->get('dateEnd'))) {
                    $query->whereHas('order', function ($query) use ($request) {
                        $newDateEnd = Carbon::createFromFormat('Y-m-d', $request->get('dateEnd'));
                        $query->whereDate('created_at', '<=', $newDateEnd);
                    });
                }
            })
            ->rawColumns(['date', 'code', 'delivery', 'call', 'report', 'macro'])
            ->make(true);
    }

    public function deliveredPatient(Report $report)
    {
        return view('reports.suivi.signature', compact('report'));
    }


    public function storeSignature(Request $request)
    {
        Log::info($request->all());
        try {
            // Récupérer le report à mettre à jour
            $updated_report = Report::findOrFail(intval($request->reportId));

            // Mettre à jour les champs du report
            $updated_report->is_delivered = 1;
            $updated_report->delivery_date = now();
            $updated_report->is_called = 1;
            $updated_report->call_date = now();
            $updated_report->retriever_name = $request->signatorName;
            $updated_report->retriever_signature = $request->signature;

            // Sauvegarder les modifications
            $updated_report->save();
            return response()->json(['message' => 'Signature enregistrée avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l\'enregistrement de la signature'], 500);
        }

        // return redirect(route('report.index.suivi'))->with('success', 'Operation réussie avec succès!');
    }

    public function callUser($report)
    {
        $setting = $this->setting->find(1);
        $client = new Client();
        $accessToken = $setting->api_key_ourvoice;
        $to = '229' . $report->patient->telephone1;
        if ($report->patient->langue === 'fon') {
            $audio_url_disponible = 'https://caap.bj/wp-content/uploads/2023/06/RESULTAT-DISPONIBLE-FON-VF.mp3';
            $audio_url_non_disponible = 'https://caap.bj/wp-content/uploads/2023/05/RESULTAT-NON-DISPONIBLE-.mp3';
        } elseif ($report->patient->langue === 'anglais') {
            $audio_url_disponible = 'https://caap.bj/wp-content/uploads/2023/06/RESULTAT-DISPONIBLE-ANGLAIS-VF.mp3';
            $audio_url_non_disponible = 'https://caap.bj/wp-content/uploads/2023/05/Result-not-available.mp3';
        } else {
            $audio_url_disponible = 'https://caap.bj/wp-content/uploads/2023/06/RESULTAT-DISPONIBLE-FRANCAIS-VF.mp3';
            $audio_url_non_disponible = 'https://caap.bj/wp-content/uploads/2023/05/F.-RESULTAT-INDISPONIBLE.mp3';
        }
        $link_ourvoice_call = SettingApp::where('key', 'link_ourvoice_call')->first();

        // Pour lancer un appel 'https://staging.getourvoice.com/api/v1/calls'https://api.getourvoice.com/v1/calls
        // $responsevocal = $client->request('POST', 'https://api.getourvoice.com/v1/calls', [
        $responsevocal = $client->request('POST', $link_ourvoice_call->value, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'to' => [$to],
                // 'audio_url' => 'https://caap.bj/wp-content/uploads/2023/03/textToSpeech.mp3',
                'audio_url' => $audio_url_disponible,
            ],
        ]);

        $vocal = json_decode($responsevocal->getBody(), true);
        $report->order->status_appel = $vocal['data']['id'];
        $report->order->save();

        $appel = AppelByReport::where('report_id', $report->id)->first();
        if ($appel) {
            $appel->update([
                'appel_id' => $vocal['data']['id'],
            ]);
        } else {
            AppelByReport::create([
                'report_id' => $report->id,
                'appel_id' => $vocal['data']['id'],
            ]);
        }
    }

    public function sendSms($report)
    {
        $setting = $this->setting->find(1);
        $client = new Client();
        // $accessToken = '421|ACJ1pewuLLQKPsB8W59J1ZLoRRDsamQ87qJpVlTLs4h0Rs9D9nfKuBW1usjOuaJjIF77Md18i2kGbz6n840gdZ0vxSZaxbEPM22PLto17kfFQs9Kjt4XyZTBxVwMfp7aTMfaEjqTag6JIROGjZILh1pldzMqvvki7yzWpcMlzylqfZUBh86M1ddCFW0n1wgk3RapG0u2Bf8m7BDABelg7Umv0D0oIpVK4w5gxTuAq29ycUqk';
        $accessToken = $setting->api_key_ourvoice;
        $to = '229' . $report->patient->telephone1;
        $body = 'Bonjour c\'est l cabinet medical Anathomie pathologique adechinan situé à fifadji vos résultats d\'analyse sont maintenant disponible vous pouvez venir les recupérer à tout moment pendant nos heures d\'ouvertures. Nous sommes ouvert du Lundi au vendredi de 08h à 17h Merci de votre confiance';

        $link_ourvoice_sms = SettingApp::where('key', 'link_ourvoice_sms')->first();

        // Pour lancer un appel
        // $responsevocal = $client->request('POST', 'https://api.getourvoice.com/v1/messages', [
        $responsevocal = $client->request('POST', $link_ourvoice_sms->value, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => [
                'to' => [$to],
                'body' => $body,
                'sender_id' => 'c7e219bb-aa98-49e4-a87d-71250babaf98',
                // 'audio_url' => $audio_url_disponible,
            ],
        ]);
    }
}
