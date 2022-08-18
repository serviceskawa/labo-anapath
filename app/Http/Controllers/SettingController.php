<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function report_index()
    {
        $setting = Setting::find(1);
        // dd($setting);
        return view('settings.report.index' , compact('setting'));
    }

    public function report_store(Request $request)
    {
        $setting = Setting::find(1);

        if ($request->file('img1') ) {

            $img1 = time() . '_settings_report_signature1' . $request->file('img1')->extension();  
            
            $path_img1 = $request->file('img1')->storeAs('settings/reports', $img1, 'public');

        }
        if ($request->file('img2') ) {

            $img2 = time() . '_settings_report_signature2' . $request->file('img2')->extension();  
            
            $path_img2 = $request->file('img2')->storeAs('settings/reports', $img2, 'public');
            
        }
        if ($request->file('img3') ) {

            $img2 = time() . '_settings_report_signature3' . $request->file('img3')->extension();  
            
            $path_img3 = $request->file('img3')->storeAs('settings/reports', $img2, 'public');
            
        }

        if ($setting) {

            $setting->fill([
                "signatory1" => $request->Signator1,
                "signatory2" => $request->Signator2,
                "signatory3" => $request->Signator3,
                "placeholder" => $request->placeholder,
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
                "signature1" => $request->file('img1') ? $path_img1 : "",
                "signature2" => $request->file('img2') ? $path_img2 : "",
                "signature3" => $request->file('img3') ? $path_img3 : ""
            ]);
        }
        

        return back()->with('success', " Elément mis à jour avec succès  ! ");
    }

    public function report_store_placeholder(Request $request)
    {
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
}
