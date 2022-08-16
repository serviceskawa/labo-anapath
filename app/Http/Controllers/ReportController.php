<?php

namespace App\Http\Controllers;

use App\Models\Report;

use App\Models\Contrat;
use App\Models\Setting;
use App\Helpers\herpers;
use Illuminate\Http\Request;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $report = Report::findorfail($request->report_id);
        $report->fill([
            "title" => $request->title,
            "description" => $request->content,
            "signatory1" => $request->signatory1 ? '1' : '0',
            "signatory2" => $request->signatory2 ? '1' : '0',
            "signatory3" => $request->signatory3 ? '1' : '0'
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function send_sms($id)
    {
        $report = Report::findorfail($id);

        $tel = $report->patient->telephone1;
        $number = "+22996631611";
        $message = "test one";
        
        try {

            sendSingleMessage($number, $message);

            return redirect()->back()->with('success', "SMS envoyé avec succes ");

            } catch(\Throwable $ex){

          return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }

    }
}
