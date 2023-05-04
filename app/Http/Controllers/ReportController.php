<?php

namespace App\Http\Controllers;

use App\Models\Report;

//use App\Models\Contrat;
use App\Models\Doctor;
use App\Models\LogReport;
use App\Models\Setting;
use GuzzleHttp\Client;
//use App\Helpers\herpers;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Spipu\Html2Pdf\Html2Pdf;



// require _DIR_.'/vendor/autoload.php';
use App\Models\SettingReportTemplate;

use App\Models\TitleReport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use QRcode as GlobalQRcode;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    protected $report;
    protected $doctor;
    protected $logReport;
    protected $settingReportTemplate;
    protected $titleReport;
    protected $setting;
    protected $user;
    /**
     * ReportController constructor.
     * Instanciate Report, Doctor and LogReport classes
     */
    public function __construct(Report $report, Doctor $doctor, User $user, LogReport $logReport, TitleReport $titleReport, SettingReportTemplate $settingReportTemplate, Setting $setting)
    {
        $this->middleware('auth');
        $this->report = $report;
        $this->doctor = $doctor;
        $this->logReport = $logReport;
        $this->titleReport = $titleReport;
        $this->settingReportTemplate = $settingReportTemplate;
        $this->setting = $setting;
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $reports = $this->report->orderBy('created_at', 'DESC')->get();
        $doctors = $this->doctor->all();
        $user = Auth::user();


        return view('reports.index', compact('reports', 'doctors'));
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
        $user = Auth::user();

        $report = $this->report->findorfail($request->report_id);
        $report->fill([
            "description" => $request->content,
            "signatory1" => $doctor_signataire1,
            "signatory2" => $doctor_signataire2,
            "signatory3" => $doctor_signataire3,
            "status" => $request->status == "1" ? '1' : '0',
            "title" => $request->title,
            "description_supplementaire" => $request->description_supplementaire != "" ? $request->description_supplementaire : '',
        ])->save();

        $log = new LogReport();
        $log->operation = "Mettre à jour ";
        $log->report_id = $request->report_id;
        $log->user_id = $user->id;
        $log->save();

        return redirect()->back()->with('success', "   Examen mis à jour ! ");
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
        $templates = $this->settingReportTemplate->all();
        $titles = $this->titleReport->all();
        $logs = $this->logReport->where('report_id', 'like', $report->id)->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('reports.show', compact('report', 'setting', 'templates', 'titles', 'logs'));
    }

    // public function send_sms($id)
    // {
    //     if (!getOnlineUser()->can('edit-reports')) {
    //         return back()->with('error', "Vous n'êtes pas autorisé");
    //     }
    //     $report = $this->report->findorfail($id);

    //     $tel = $report->patient->telephone1;
    //     $number = "+22996631611";
    //     $message = "test one";
    //     $user = Auth::user();

    //     try {

    //         sendSingleMessage($tel, $message);
    //         $log = new LogReport();
    //         $log->operation = "Evoyer un message";
    //         $log->report_id = $id;
    //         $log->user_id = $user->id;
    //         $log->save();

    //         return redirect()->back()->with('success', "SMS envoyé avec succes ");
    //     } catch (\Throwable $ex) {

    //         return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
    //     }
    // }

    public function pdf($id)
    {
        if (!getOnlineUser()->can('edit-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        //dd($report);
        $report = $this->report->find($id);
        $setting = $this->setting->find(1);
        $user = Auth::user();
        $qrCode = new QrCode('test');
        $qrCode->setSize(300);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrPng = $report->code .'_qrcode.png';

        // Save it to a file {{ asset('storage/' . $signature1) }}
        // $result->saveToFile();
        $result->saveToFile('storage/settings/app/'.$qrPng);

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        // $qrCodeDataUri = $qrCode->writeDataUri();
        //dd($dataUri);

        if ($report->signatory1 != null) {
            $signatory1 = $this->user->findorfail($report->signatory1);
        }

        if ($report->signatory2 != null) {
            $signatory2 = $this->user->findorfail($report->signatory2);
        }

        if ($report->signatory3 != null) {
            $signatory3 = $this->user->findorfail($report->signatory3);
        }
        $year_month = "";
        if ($report->patient->year_or_month != 1) {
            $year_month = "mois";
        } else {
            $year_month = "ans";
        }

        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Africa/Porto-Novo');
        //date_format($report->updated_at,"d/m/Y")



        $data = [
            'code' => $report->code,
            'current_date' => utf8_encode(strftime('%d/%m/%Y')),
            'prelevement_date' => date('d/m/Y', strtotime($report->order->prelevement_date)),
            'test_affiliate' => $report->order->test_affiliate,
            'qrcode' => $dataUri,
            'title' => $report->title,
            'content' => $report->description,
            'content_supplementaire' => $report->description_supplementaire != "" ? $report->description_supplementaire : '',
            'signatory1' => $report->signatory1 != null ? $signatory1->lastname . ' ' . $signatory1->firstname : '',
            'signature1' => $report->signatory1 != null ? $signatory1->signature : '',


            'signatory2' => $report->signatory2 != null ? $signatory2->lastname . ' ' . $signatory2->firstname : '',
            'signature2' => $report->signatory2 != null ? $signatory2->signature : '',

            'signatory3' => $report->signatory3 != null ? $signatory3->lastname . ' ' . $signatory3->firstname : '',
            'signature3' => $report->signatory3 != null ? $signatory3->signature : '',

            'patient_firstname' => $report->patient->firstname,
            'patient_lastname' => $report->patient->lastname,
            'patient_age' => $report->patient->age,
            'patient_year_or_month' => $year_month,
            'patient_genre' => $report->patient->genre,
            'footer' => $setting->footer,
            'hospital_name' => $report->order ? $report->order->hospital->name : '',
            'doctor_name' => $report->order ? $report->order->doctor->name : '',
            'created_at' => date_format($report->created_at, "d/m/Y"),
            'date' => date('d/m/Y')
        ];

        //dd($data);

        try {
            $log = new LogReport();
            $log->operation = "Imprimer";
            $log->report_id = $id;
            $log->user_id = $user->id;
            $log->save();
            $content = view('pdf/canva', $data)->render();

            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 0);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->setTestTdInOnePage(false);
            // $html2pdf->setProtection(['copy', 'print'], 'user', 'password');
            $html2pdf->__construct(
                $orientation = 'P',
                $format = 'A4',
                $lang = 'fr',
                $unicode = true,
                $encoding = 'UTF-8',
                $margins = array(8, 20, 8, 8),
                $pdfa = false
            );
            $html2pdf->writeHTML($content);
            // Définir le mot de passe de protection
            //$password = '1234@2023';

            // Définir les permissions du document
            // $permissions = array(
            //     'print',
            //     'modify',
            //     'copy',
            //     'annot-forms'
            // );

            // Appliquer la protection
            //$html2pdf->pdf->SetProtection($permissions, $password, $password);
            $newname = 'CO-' . $report->order->code . '.pdf';
            $html2pdf->output($newname);
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
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
        if (!getOnlineUser()->can('edit-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = $this->report->findorfail($reportId);

        if (empty($report)) {
            return redirect()->back()->with('error', "Ce compte rendu n'existe pas. Veuillez ressayer ! ");
        }

        $report->fill([
            "is_deliver" => 1,
        ])->save();

        $this->pdf($reportId);
        // dd($report);
        //return redirect()->back()->with('success', "Effectué avec succès ! ");
    }

    public function getReportsforDatatable(Request $request)
    {

        $data = $this->report->latest();


        return DataTables::of($data)->addIndexColumn()
            ->editColumn('code', function ($data){
                //change over here
                //return date('y/m/d',$data->created_at);
                if($data->order)
                {
                    return $data->order->code;
                }else{
                    return '';
                }
            })

            ->addColumn('codepatient', function ($data) {

                return $data->patient->code;

                // return Invoice::whereMonth('updated_at', )->sum('total');
            })
            ->addColumn('patient', function ($data) {

                return $data->patient->firstname . ' ' . $data->patient->lastname;

                // return Invoice::whereMonth('updated_at', )->sum('total');
            })
            ->addColumn('telephone', function ($data) {
                return $data->patient->telephone1;
            })
            ->addColumn('created_at', function ($data){

               return \Carbon\Carbon::parse($data->created_at)->format('d/m/Y');
            })
            ->addColumn('status', function ($data) {
                if ($data->status==1) {
                    return 'Valider';
                } else {
                    return 'Attente';
                }
            })
            ->addColumn('action', function ($data){
                $btnVoir = '<a type="button" href="'. route('report.show', $data->id) .'"class="btn btn-primary"><i class="mdi mdi-eye"></i> </a>';
                // $btnVoir = '<a type="button" href="' . route('details_test_order.index', $data->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>';
                // $btnEdit = ' <a type="button" href="' . route('test_order.edit', $data->id) . '" class="btn btn-primary" title="Mettre à jour examen"><i class="mdi mdi-lead-pencil"></i></a>';
                if ($data->order) {
                    $btnReport = ' <a type="button" href="'.route('details_test_order.index', $data->order->id).'" class="btn btn-warning" title="Demande '.$data->order->code.'"><i class="uil-file-medical"></i> </a>';
                    // $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';
                    // $btnreport = "";
                }
                 else {
                    $btnReport = ' ';
                }

                if ($data->status == 1) {
                    if ($data->is_deliver==1) {
                        $btnInvoice = ' <a type="button" href="'. route('report.updateDeliver', $data->id) .'" class="btn btn-success">Imprimer </a>';
                    } else {
                        $btnInvoice = ' <a type="button" href="'. route('report.updateDeliver', $data->id) .'" class="btn btn-warning">Imprimer </a>';
                    }

                }else{
                    $btnInvoice='';
                }


                return $btnVoir .  $btnReport . $btnInvoice;
            })
            // ->filter(function ($query) use ($request) {


            // })
            ->make(true);

    }


}
