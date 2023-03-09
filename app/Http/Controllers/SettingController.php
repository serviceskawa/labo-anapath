<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TitleReport;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function report_index()
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        $titles = TitleReport::latest()->get();
        return view('settings.report.index' , compact('titles'));
    }

    public function report_store(Request $request)
    {
        if (!getOnlineUser()->can('create-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'title' => 'required |unique:title_reports,title',
        ]);

        try {
            TitleReport::create([
                "title" => strtoupper($data['title']),
            ]);
            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }

        return back()->with('success', " Elément mis à jour avec succès  ! ");
    }

    public function report_edit($id)
    {
        // if (!getOnlineUser()->can('view-settings')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = TitleReport::find($id);
        return response()->json($data);
    }

    public function report_update(Request $request)
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'id2' => 'required',
            'title2' => 'required'
        ]);


        try {

            $titleReport = TitleReport::find($data['id2']);
            $titleReport->title = strtoupper($data['title2']);
            $titleReport->save();

            return back()->with('success', "Un titre mis à jour ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de la mis à jour ! " . $ex->getMessage());
        }
    }

    public function report_delete($id)
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $titleReport = TitleReport::find($id)->delete();

        if ($titleReport) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }

    public function report_store_placeholder(Request $request)
    {
        if (!getOnlineUser()->can('create-settings') ) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        if (!getOnlineUser()->can('edit-settings') ) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $setting = Setting::find(1);

        if ($setting) {

            $setting->fill([
                "placeholder" => $request->placeholder
            ])->save();

        }else {
            $setting = Setting::create([
                "placeholder" => $request->placeholder
            ]);
        }
        return back()->with('success', "Placeholder mis à jour avec succès  ! ");
    }

    public function app()
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        // dd($setting);
        return view('settings.app.index' , compact('setting'));
    }

    public function app_store(Request $request)
    {
        if (!getOnlineUser()->can('create-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // dd($request);
        $setting = Setting::find(1);

        dd($request);

        if ($request->file('logo') ) {

            $logo = time() . '_settings_app_logo.' . $request->file('logo')->extension();

            $path_logo = $request->file('logo')->storeAs('settings/app', $logo, 'public');

        }
        if ($request->file('favicon') ) {

            $favicon = time() . '_settings_app_favicon.' . $request->file('favicon')->extension();

            $path_favicon = $request->file('favicon')->storeAs('settings/app', $favicon, 'public');

        }
        if ($request->file('img3') ) {

            $img3 = time() . '_settings_app_blanc.' . $request->file('img3')->extension();

            $path_img3 = $request->file('img3')->storeAs('settings/app', $img3, 'public');

        }

        if ($setting) {

            $setting->fill([
                "titre" => $request->titre,
                "logo" => $request->file('logo') ? $path_logo : $setting->logo,
                "favicon" => $request->file('favicon') ? $path_favicon : $setting->favicon,
                "logo_blanc" => $request->file('img3') ? $path_img3 : $setting->logo_blanc,
                "server_sms" => $request->server_sms,
                "api_key_sms" => $request->api_key_sms,
            ])->save();

        }else {
            $setting = Setting::create([
                "titre" => $request->titre,
                "logo" => $request->file('logo') ? $path_logo : "",
                "favicon" => $request->file('favicon') ? $favicon : "",
                "logo_blanc" => $request->file('img3') ? $path_img3 : "",
                "server_sms" => $request->server_sms,
                "api_key_sms" => $request->api_key_sms,
            ]);
        }


        return back()->with('success', " Elément mis à jour avec succès  ! ");
    }
}
