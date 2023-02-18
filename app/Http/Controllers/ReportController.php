<?php

namespace App\Http\Controllers;

use App\Models\Report;

use App\Models\Contrat;
use App\Models\Setting;
use App\Helpers\herpers;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;



// require _DIR_.'/vendor/autoload.php';
use App\Models\SettingReportTemplate;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-compte-rendu')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $reports = Report::all();

        return view('reports.index', compact('reports'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-compte-rendu')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'content' => 'required',
            'report_id' => 'required|exists:reports,id',
            'status' => 'required|boolean', 
            // 'signatory1' => 'nullable|required_if:signatory1,on',
        ]);

        $report = Report::findorfail($request->report_id);
        $report->fill([
            "description" => $request->content,
            "signatory1" => $request->signatory1 ? '1' : '0',
            "signatory2" => $request->signatory2 ? '1' : '0',
            "signatory3" => $request->signatory3 ? '1' : '0',
            "status" => $request->status == "1" ? '1' : '0'
        ])->save();

        return redirect()->back()->with('success', "   Examen finalisé ! ");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!getOnlineUser()->can('view-compte-rendu')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = Report::findorfail($id);
        $setting = Setting::find(1);
        $templates = SettingReportTemplate::all();

        return view('reports.show', compact('report', 'setting', 'templates'));
    }

    public function send_sms($id)
    {
        if (!getOnlineUser()->can('edit-compte-rendu')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = Report::findorfail($id);

        $tel = $report->patient->telephone1;
        $number = "+22996631611";
        $message = "test one";

        try {

            sendSingleMessage($tel, $message);

            return redirect()->back()->with('success', "SMS envoyé avec succes ");
        } catch (\Throwable $ex) {

            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function pdf($id)
    {

        if (!getOnlineUser()->can('edit-compte-rendu')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = Report::findorfail($id);
        $setting = Setting::find(1);

        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Africa/Porto-Novo');
        //date_format($report->updated_at,"d/m/Y")

        $data = [
            'code' => $report->code,
            'current_date' => utf8_encode(strftime('%d/%m/%Y')),
            'prelevement_date' => date('d/m/Y', strtotime($report->order->prelevement_date)),
            'content' => $report->description,
            'signatory1' => $report->signatory1 == '1' ? $setting->signatory1 : '',
            'signature1' => $report->signatory1 == '1' ? $setting->signature1 : '',
            'signatory2' => $report->signatory2 == '1' ? $setting->signatory2 : '',
            'signature2' => $report->signatory2 == '1' ? $setting->signature2 : '',
            'signatory3' => $report->signatory3 == '1' ? $setting->signatory3 : '',
            'signature3' => $report->signatory3 == '1' ? $setting->signature3 : '',
            'patient_firstname' => $report->patient->firstname,
            'patient_lastname' => $report->patient->lastname,
            'patient_age' => $report->patient->age,
            'patient_genre' => $report->patient->genre,
            'hospital_name' => $report->order->hospital->name,
            'doctor_name' => $report->order->doctor->name,
            'created_at' => date_format($report->created_at, "d/m/Y"),
            'date' => date('d/m/Y')
        ];

        try {
            $content = view('pdf/canva', $data)->render();

            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 0);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->setTestTdInOnePage(false);
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

        $template = SettingReportTemplate::findorfail($request->id);

        return response()->json($template, 200);
    }

    // Met à jour le statut livré
    public function updateDeliverStatus($reportId)
    {
        if (!getOnlineUser()->can('edit-compte-rendu')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $report = Report::findorfail($reportId);

        if (empty($report)) {
            return redirect()->back()->with('error', "Ce compte rendu n'existe pas. Veuillez ressayer ! ");
        }
        $report->fill([
            "is_deliver" => 1,
        ])->save();
        // dd($report);
        return redirect()->back()->with('success', "Ce compte rendu a été livré ! ");
    }
}
