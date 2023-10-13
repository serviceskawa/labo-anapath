<?php

namespace App\Http\Controllers;

use App\Models\AssignmentDoctor;
use App\Models\Report;
use App\Models\SettingApp;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class AssignmentDoctorController extends Controller
{
    public function index()
    {
       $doctors = getUsersByRole('docteur');
        return view('reports.assignment.index',compact('doctors'));
    }

    public function create($id)
    {
        $reports = Report::latest()->get();
        $doctor = User::find($id);
        // dd($doctor);
        return view('reports.assignment.create',compact('reports','doctor'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $reportIds = $request->input('report_id');
        $comments = $request->input('comment');
        $doctor_id = $request->id;

        try {
            foreach ($reportIds as $key => $reportId) {
                $comment = $comments[$key];
                AssignmentDoctor::create([
                    'doctor_id'=>$doctor_id,
                    'report_id'=>$reportId,
                    'comment'=>$comment
                ]);
            }
            return redirect()->route('report.assignment.index');
        } catch (\Throwable $th) {
            return back()->with('error','Erreur d\'enregistrement'.$th);
        }


    }

    public function pdf($id)
    {
        if (!getOnlineUser()->can('edit-reports')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $doctors = getUsersByRole('docteur');

        $doctor =User::find($id);

        foreach ($doctors as $key => $value) {
            if ($value->id == $id) {
                $doctor = $value;
                break;
            }
        }
        // dd($doctor);
        $assignments = $doctor->assignment;


        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Africa/Porto-Novo');
        //date_format($report->updated_at,"d/m/Y");


        $data = [
            'doctor'=>$doctor->firsname.' '.$doctor->lastname,
            'assignments'=>$assignments,
            'footer' => SettingApp::where('key','report_footer')->first()->value,
            'date' => date('d/m/Y'),
        ];

        //dd($data);

        try {

            $content = view('reports/assignment/pdf', $data)->render();

            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 0);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->setTestTdInOnePage(false);
            // $html2pdf->setProtection(['copy', 'print'], 'user', 'password');
            $html2pdf->__construct($orientation = 'P', $format = 'A4', $lang = 'fr', $unicode = true, $encoding = 'UTF-8', $margins = [8, 20, 8, 8], $pdfa = false);
            $html2pdf->writeHTML($content);

            $newname = 'Affectation-' . $doctor->firstname . '.pdf';
            $html2pdf->output($newname);
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
    }
}
