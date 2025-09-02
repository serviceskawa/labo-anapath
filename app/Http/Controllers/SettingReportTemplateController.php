<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
        if (!getOnlineUser()->can('view-setting-report-templates')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $templates = (new SettingReportTemplate)->latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('templates.reports.index', compact('templates',));
    }

    public function create()
    {
        if (!getOnlineUser()->can('create-setting-report-templates')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $template = "";
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('templates.reports.create', compact('template'));
    }

    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-setting-report-templates')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $data = $this->validate($request, [
            'titre' => 'required',
            'content'=>'required'
        ]);

        try {
            (new SettingReportTemplate)->updateorcreate(["id" => $request->id], [
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
        if (!getOnlineUser()->can('edit-setting-report-templates')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $template = (new SettingReportTemplate)->findorfail($id);
        return view('templates.reports.create', compact('template'));
    }

    public function delete($id)
    {
        if (!getOnlineUser()->can('delete-setting-report-templates')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $template = (new SettingReportTemplate)->find($id)->delete();

        if ($template) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }
}
