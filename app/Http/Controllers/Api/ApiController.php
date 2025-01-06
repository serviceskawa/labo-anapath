<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppelTestOder;
use App\Models\Report;
use Carbon\Carbon;
use DateTime;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;

class ApiController extends Controller
{
    public function getCode($code){
        $report = $this->getCodeByDB($code);
        $signatory1 = "";
        $signatory2 = "";
        $signatory3 = "";

        if($report){
            if($report->signatory1 !=null ){
                $signatory1 = getSignatory1($report->signatory1);
            }
            if($report->signatory2 ){
                $signatory2 = getSignatory1($report->signatory2);
            }
            if($report->signatory3 ){
                $signatory3 = getSignatory1($report->signatory3);
            }

            return response()->json(['status'=>200,'data'=>$report,'signatory1'=>$signatory1,'signatory2'=>$signatory2,'signatory3'=>$signatory3],200);
        }else{
            return response()->json(['status'=>'error'],500);
        }
    }

    public function getCodeByDB(Request $request){
        $code = $request->input('codeByDB');
        //recuppérer les deux premiers caractère de la chaine
        $beginCode = substr($code, 0,2);
        //récupérer les caractères à partir de la position 3
        $endCode = substr($code,2);

        $codeGenerate = $beginCode.'-'.$endCode;
        $report = Report::with(['patient'])->whereHas('order',function($query)use($codeGenerate){
            $query->where('code','like', '%'.$codeGenerate);
        })->first();
        return $report;
    }

    public function getCodeDB(Request $request){
        $code = $request->input('codeByDB');
         //recuppérer les deux premiers caractère de la chaine
         $beginCode = substr($code, 0,2);
         //récupérer les caractères à partir de la position 3
         $endCode = substr($code,2);

        $codeGenerate = $beginCode.'-'.$endCode;
        $report = Report::with(['patient'])->whereHas('order',function($query)use($codeGenerate){
            $query->where('code','like', '%'.$codeGenerate);
        })->first();
        if($report->status){
            return response()->json(['code'=>$report->order->code],200);
            // return response()->status(200);
            // return response()->setStatusCode(200) ;
        }else{
            // return response()->status(500);
            return response()->json(['status'=>'Non trouvé'],500);
        }
    }

    public function getStatus(Request $request)
    {
        $data = $request->all();


        $appel = AppelTestOder::where('voice_id','like',$data['voice_id'])->first();

        if(!$appel)
        {
             AppelTestOder::create([
            "type"=>$data['type'],
            "account_id"=>$data['account_id'],
            "voice_id"=>$data['voice_id'],
            "event"=>$request->event,
            ]);
            return response()->json(['status'=>'Nouvelle ligne'],200);
        }else {
            $appel->update([
                "type"=>$data['type'],
                "account_id"=>$data['account_id'],
                "voice_id"=>$data['voice_id'],
                "event"=>$data['event']
            ]);
            return response()->json(['status'=>'ligne modifié'],200);
        }

    }

    public function pdf(Request $request)
    {

        // return response()->json($request->patient['firstname']);

        $text = $request->code;
        $qrCode = new QrCode($text);
        $qrCode->setSize(300);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrPng = $text . '_qrcode.png';

        // Save it to a file {{ asset('storage/' . $signature1) }}
        // $result->saveToFile();
        $result->saveToFile('storage/settings/app/' . $qrPng);

        // Generate a data URI to include image data inline (i.e. inside an <img> tag)
        $dataUri = $result->getDataUri();

        // $qrCodeDataUri = $qrCode->writeDataUri();

        $signatory1 = $request->signatory1 ?? '';
        $signatory2 = $request->signatory2 ?? '';
        $signatory3 = $request->signatory3 ?? '';


        $birthday = $request->patient['birthday'];
        $age = '';

        if ($birthday) {
            // Convertir la date de naissance en objet DateTime
            $birthdate = new DateTime($birthday);

            // Obtenir la date actuelle
            $currentDate = new DateTime();

            // Calculer la différence entre les deux dates
            $ageInterval = $birthdate->diff($currentDate);

            // Obtenir l'âge sous forme d'années, mois et jours
            $ageYears = $ageInterval->y;
            $ageMonths = $ageInterval->m;
            $ageDays = $ageInterval->d;

            // Afficher l'âge
            if ($ageYears > 0) {
                $age = $ageYears . ' ans';
            } elseif ($ageMonths > 0) {
                $age = $ageMonths . ' mois';
            } else {
                $age = $ageDays . ' jours';
            }
        } else {
            $age = 'Date de naissance non spécifiée';
        }

        // Utilisez la variable $age comme nécessaire dans votre code


        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Africa/Porto-Novo');
        //date_format($report->updated_at,"d/m/Y");


        $data = [
            'code' => $request->code,
            'current_date' => utf8_encode(strftime('%d/%m/%Y')),
            'prelevement_date' => $request->testOrder ? date('d/m/Y', strtotime($request->testOrder['prelevementDate'])) : '',
            'test_affiliate' => $request->testOrder ? $request->testOrder['test_affiliate'] : '',
            'qrcode' => $dataUri,
            'title' => $request->title,
            'content' => $request->description,
            'content_micro' => $request->descriptionMicro,
            'content_supplementaire' => $request->descriptionSupplementaire ? $request->descriptionSupplementaire : '',
            'content_supplementaire_micro' => $request->descriptionSupplementaireMicro ? $request->descriptionSupplementaireMicro : '',

            'signatory1' => $request->signatory1 ? $signatory1 : '',
            'signatory2' => $request->signatory2 ? $signatory2 : '',
            'signatory3' => $request->signatory3 ? $signatory3 : '',

            'patient_firstname' => $request->patient['firstname'],
            'patient_lastname' => $request->patient['lastname'],
            'patient_age' => $age,
            'patient_genre' => $request->patient['genre'],
            'status' => $request->status,
            'header' => $request->header,
            'footer' => $request->footer,
            'hospital_name' => $request->testOrder ? ($request->testOrder['hospital'] ? $request->testOrder['hospital']['name'] : '') :'',
            'doctor_name' => $request->testOrder ? ($request->testOrder['doctor'] ? $request->testOrder['doctor']['name'] : '') :'',
            'created_at' => Carbon::parse($request->createdAt)->format('d/m/Y'),
            'date' => date('d/m/Y'),
        ];


        try {

            $content = view('pdf/canvaApi', $data)->render();

            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 0);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->setTestTdInOnePage(false);
            // $html2pdf->setProtection(['copy', 'print'], 'user', 'password');
            $html2pdf->__construct($orientation = 'P', $format = 'A4', $lang = 'fr', $unicode = true, $encoding = 'UTF-8', $margins = [8, 20, 8, 8], $pdfa = false);
            // $html2pdf->writeHTML($content);
            try {
                // Écrit le contenu dans le PDF
                $html2pdf->writeHTML($content);
            } catch (\Exception $e) {
                // Affiche l'erreur ou enregistre-la dans les logs
                return response()->json('Erreur lors de la génération du PDF : ' . $e->getMessage());
            }
            $newname = 'CO-' . $request->code . '.pdf';
            return response()->json(200);
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            return response()->json($formatter->getHtmlMessage());
        }
    }
}
