<?php

namespace App\Http\Controllers;

use App\Models\Report;

use App\Models\Contrat;
use App\Models\Setting;
use App\Helpers\herpers;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


// require _DIR_.'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

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

    public function pdf1($id)
    {
        // $report = Report::findorfail($id);
        // $setting = Setting::find(1);
        // $data = [
        //     'title' => $report->title,
        //     'content' => $report->description,
        //     'signatory1' => $report->signatory1 == '1' ? $setting->signatory1 : '',
        //     'signature1' => $report->signatory1 == '1' ? $setting->signature1 : '',
        //     'patient' => $report->patient->name,
        //     'date' => date('m/d/Y')
        // ];
          
        // // $pdf = PDF::loadView('pdf.report', $data);
        $pdf = PDF::loadView('pdf.canva');
        return view('pdf.canva');
        return $pdf->download('canva.pdf');
    }
    public function pdf($id){

        $report = Report::findorfail($id);
        $setting = Setting::find(1);
        $data = [
            'title' => $report->title,
            'content' => $report->description,
            'signatory1' => $report->signatory1 == '1' ? $setting->signatory1 : '',
            'signature1' => $report->signatory1 == '1' ? $setting->signature1 : '',
            'patient' => $report->patient->name,
            'date' => date('m/d/Y')
        ];
        $html2pdf = new Html2Pdf();
        // border: 3px solid #73AD21;
        $html2pdf->setDefaultFont('Helvetica');//ici tu mets le font familly que tu veux utiliser dans ton pdf (tu peux changer comme tu veux dans chaque compartiment de ton code)

        // $html2pdf->addFont($family, $style = '', $file = '');
        $html2pdf->__construct(
            $orientation = 'P',
            $format = 'A4',
            $lang = 'fr',
            $unicode = true,
            $encoding = 'UTF-8',
            $margins = array(8, 20, 8, 8),
            $pdfa = false
        );
        $html2pdf->writeHTML('
            <div style="display:inline-block; ">
                <span style="display: inline-block;padding-top: 5px; font-size:25px; margin-top:-50px "><img src="'.public_path('adminassets/images/Labo_Logo_1.png').'" alt=""></span> 
                <div style="display: inline-block; padding: 5px; position: absolute; top:20px; right: 0px; padding: 10px; text-align:right;">
                    <b><span style="font-size:17px; text-align:right;" >  CENTRE ADECHINA ANATOMIE PATHOLOGIE</span></b>
                    <br><span style="font-size:10px; text-align:right;" >Laboratoire d’Anatomie Pathologique</span>
                </div> 
            </div>
            <div style="display: inline-block; position: absolute; right: 0px;width: 150px;padding: 10px;  margin-top:-20px">
                <p>
                    <b>N° ANAPTH :</b> XXXXXX
                    <br>
                    <b>Date :</b> 14 Juillet 2022
                </p>
            </div> 
            <div style="margin-top:20px; background-color:#0070C1; width:100%; height:50px;color:rgb(255,255,255); text-align: center; padding-top:19px;font-size:25px;">
                <b>COMPTE RENDU HISTOPATHOLOGIE</b>
            </div>
            <br><br>
            <div>
                <fieldset style="border: 3px solid rgb(0,0,0,0)">
                    <legend style="font-size: 1.5em;padding: 5px;border:none; background-color:rgb(255,255,255);">
                        <b>Informations prélèvement</b>
                    </legend>
                    <p style="margin-left:10px; margin-right:10px; display:block; width: 100%;">
                    

                       <table style="max-width: 100%;width: 500px ">
                            <tbody>
                                <tr>
                                    <th>Nom :</th>
                                    <td>'.$report->patient->firstname.' </td>
                                    <th>Date prélèvement : </th>
                                    <td>12 Juillet 2022 </td>
                                </tr>
                                <tr>
                                    <th>Prénoms :</th>
                                    <td>'.$report->patient->lastname.' </td>
                                    <th>Date d’arrivée labo : </th>
                                    <td>13 Juillet 2022 </td>
                                </tr>
                                <tr>
                                    <th>Age :</th>
                                    <td>'.$report->patient->age.' </td>
                                    <th>Service demandeur  </th>
                                    <td>'.$report->order->hospital->name.' </td>
                                </tr>
                                <tr>
                                    <th>Sexe :</th>
                                    <td>'.$report->patient->genre.' </td>
                                    <th>Médecin prescripteur : </th>
                                    <td>'.$report->order->doctor->name.' </td>
                                </tr>
                                <tr><td colspan=4></td></tr>
                                <tr><td colspan=4></td></tr>
                                <tr><td colspan=4></td></tr>
                                <tr><td colspan=4></td></tr>
                                <tr >
                                    <th>Infos complémentaire *: </th>
                                    <td >it maecenas tortor. Donec.</td>
                                
                                    <th>Commentaire *: </th>
                                    <td >it maecenas tortor. Donec </td>
                                </tr>
                            </tbody>
                       </table>
                    </p>
                    <br>
                </fieldset>
            </div>
            <br><br>
            <div >
                <fieldset style="border: 3px solid rgb(0,0,0,0)">
                    <legend style="font-size: 1.5em;padding: 5px;border:none; background-color:rgb(255,255,255);">
                        <b>Récapitulatifs </b>
                    </legend>
                    <p style="margin-left:10px; margin-right:10px; display: inline-block; width: 75%;font-family:Courier; font-size:14px; ">
                        <b>Examen / Type </b> : Cytoponction Mammaire
                        <p style="text-justify; width: -moz-fit-content; width: fit-content;">
                            '.htmlspecialchars_decode($report->description).'
                        </p>
                    </p>
                    <br>
                </fieldset>
            </div>
            <div style="">
                <p style="text-align:right; margin-right:250px; font-size:20px; font-family:courier;">Signature 
                <br>
                <b> <i style="font-size:10px; color: blue">Dr. Jane DOE
                <br>
                Spécialité</i></b>
                </p>
                <p style="text-align:right;margin-top:-70px; font-size:20px; font-family:courier;">Signature
                    <br>
                    <b> <i style="font-size:10px; color: blue">Dr. Jane DOE
                    <br>
                    Spécialité</i></b>
                </p>
            </div>
            <br><br>
            <div style="align-text:center;font-family:courier;font-size:12px;">
                Centre ADECHINA Anatomie Pathologique • <br>
                Adresse : XXXXXXXXXXXXXX • Téléphone : XXXXXXXX/XXXXXXXX • RCCM XXXXXXXXXXXXXXXXXXXXXXXXXXXX
                <br>
                Contact@caap.bj • Ouvert du Lundi au Vendredi de 08:00 - 12:00 / 14:00 - 18:00 • www.caap.bj
            </div>
  
        ');
        $html2pdf->output();
    }
}
