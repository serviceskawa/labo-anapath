<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
        // dd($setting);
        return view('settings.report.index' , compact('setting'));
    }

    public function report_store(Request $request)
    {
        if (!getOnlineUser()->can('create-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $setting = Setting::find(1);

        if ($request->file('img1') ) {

            $img1 = time() . '_settings_report_signature1.' . $request->file('img1')->extension();  
            
            $path_img1 = $request->file('img1')->storeAs('settings/reports', $img1, 'public');

        }
        if ($request->file('img2') ) {

            $img2 = time() . '_settings_report_signature2.' . $request->file('img2')->extension();  
            
            $path_img2 = $request->file('img2')->storeAs('settings/reports', $img2, 'public');
            
        }
        if ($request->file('img3') ) {

            $img2 = time() . '_settings_report_signature3.' . $request->file('img3')->extension();  
            
            $path_img3 = $request->file('img3')->storeAs('settings/reports', $img2, 'public');
            
        }

        if ($setting) {

            $setting->fill([
                "signatory1" => $request->Signator1,
                "signatory2" => $request->Signator2,
                "signatory3" => $request->Signator3,
                "placeholder" => $request->placeholder,
                "placeholder2" => $request->placeholder2,
                "signature1" => $request->file('img1') ? $path_img1 : $setting->signature1,
                "signature2" => $request->file('img2') ? $path_img2 : $setting->signature2,
                "signature3" => $request->file('img3') ? $path_img3 : $setting->signature3
            ])->save();

        }else {
            $setting = Setting::create([
                "signatory1" => $request->Signator1,
                "signatory2" => $request->Signator2,
                "signatory3" => $request->Signator3,
                "placeholder" => $request->placeholder,
                "placeholder2" => $request->placeholder2,
                "signature1" => $request->file('img1') ? $path_img1 : "",
                "signature2" => $request->file('img2') ? $path_img2 : "",
                "signature3" => $request->file('img3') ? $path_img3 : ""
            ]);
        }
        

        return back()->with('success', " Elément mis à jour avec succès  ! ");
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
