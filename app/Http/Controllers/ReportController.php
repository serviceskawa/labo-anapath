<?php

namespace App\Http\Controllers;

use App\Models\Report;

use App\Models\Contrat;
use App\Models\Setting;
use App\Helpers\herpers;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Html2Pdf;


// require _DIR_.'/vendor/autoload.php';
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::all();
        // dd($reports);
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
        $data = $this->validate($request, [
            'title' => 'required',
            'report_id' => 'required|exists:reports,id',
            'status' => 'required|boolean',
            // 'signatory1' => 'nullable|required_if:signatory1,on',
        ]);

        $report = Report::findorfail($request->report_id);
        $report->fill([
            "title" => $request->title,
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
        $report = Report::findorfail($id);
        $setting = Setting::find(1);
        // dd($report);

        return view('reports.show', compact('report', 'setting'));

    }

    public function send_sms($id)
    {
        $report = Report::findorfail($id);

        $tel = $report->patient->telephone1;
        $number = "+22996631611";
        $message = "test one";
        
        try {

            sendSingleMessage($tel, $message);

            return redirect()->back()->with('success', "SMS envoyé avec succes ");

            } catch(\Throwable $ex){

          return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }

    }

    public function pdf($id){

        $report = Report::findorfail($id);
        $setting = Setting::find(1);
        $data = [
            'title' => $report->title,
            'content' => $report->description,
            'signatory1' => $report->signatory1 == '1' ? $setting->signatory1 : '',
            'signature1' => $report->signatory1 == '1' ? $setting->signature1 : '',
            'signatory2' => $report->signatory2 == '1' ? $setting->signatory2 : '',
            'signature2' => $report->signatory2 == '1' ? $setting->signature2 : '',
            'patient_firstname' => $report->patient->firstname,
            'patient_lastname' => $report->patient->lastname,
            'patient_age' => $report->patient->age,
            'patient_genre' => $report->patient->genre,
            'hospital_name' => $report->order->hospital->name,
            'doctor_name' => $report->order->doctor->name,
            'date' => date('m/d/Y')
        ];

        try {
            $content = view('pdf/canva', $data)->render();
        
            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 0);
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
            $newname= 'CO-'.$report->order->code.'.pdf';
            $html2pdf->output($newname);
            
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
        
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }

    }
}
