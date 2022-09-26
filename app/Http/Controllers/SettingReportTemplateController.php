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
        return view('templates.reports.create');
    }

    public function store(Request $request)
    {
        // dd($request);

        $data = $this->validate($request, [
            'titre' => 'required',
            'description' => 'required',  
            'content'=>'required'        
        ]);

        try {
            SettingReportTemplate::updateorcreate(["id" => $request->id], [
                "title" => $request->titre,
                "description" => $request->description,
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
}
