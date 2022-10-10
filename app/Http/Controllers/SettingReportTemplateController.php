<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SettingReportTemplate;

class SettingReportTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function index()
    {
        $templates = SettingReportTemplate::all();
        return view('templates.reports.index', compact('templates'));
    }

    public function create()
    {
        $template = "";
        return view('templates.reports.create', compact('template'));
    }

    public function store(Request $request)
    {
        // dd($request);

        $data = $this->validate($request, [
            'titre' => 'required', 
            'content'=>'required'        
        ]);

        try {
            SettingReportTemplate::updateorcreate(["id" => $request->id], [
                "title" => $request->titre,
                "content" => $request->content,
            ]);

            return redirect()->route('template.report-index')->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    public function edit($id)
    {
        $template = SettingReportTemplate::findorfail($id);
        return view('templates.reports.create', compact('template'));
    }

    public function delete($id)
    {
        $template = SettingReportTemplate::find($id)->delete();
        
        if ($template) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }
}
