<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingInvoiceRequest;
use App\Http\Requests\TitleReportRequest;
use App\Models\Setting;
use App\Models\SettingInvoice;
use App\Models\TitleReport;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $setting;
    protected $settingInvoice;
    protected $titleReport;

    public function __construct(Setting $setting, SettingInvoice $settingInvoice, TitleReport $titleReport)
    {
        $this->middleware('auth');
         $this->setting = $setting;
         $this->settingInvoice = $settingInvoice;
         $this->titleReport = $titleReport;
    }

    public function report_index()
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        $titles = $this->titleReport->latest()->get();
        return view('settings.report.index' , compact('titles','setting'));
    }

    public function report_store(TitleReportRequest $request)
    {
        if (!getOnlineUser()->can('create-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'title' => strtoupper($request->title),
            'status' => $request->status ?1:0
        ];

        try {
            $title=$this->titleReport->create($data);

            if ($title->status ==1) {
                $titles = $this->titleReport->all();
                foreach($titles as $item)
                {
                    if($item->id != $title->id)
                    {
                        $item->update([
                            'status'=>0
                        ]);
                    }
                }
            }
            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }

        return back()->with('success', " Elément mis à jour avec succès  ! ");
    }

    public function report_edit($id)
    {

        $data = $this->titleReport->find($id);
        return response()->json($data);
    }

    public function report_update(TitleReportRequest $request)
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'id' => $request->id,
            'title' => strtoupper($request->title),
            'status' => $request->status ?1:0
        ];

        //dd($request);


        try {

            $titleReport = $this->titleReport->find($data['id']);
            $titleReport->title = $data['title'];
            $titleReport->status = $data['status'];
            $titleReport->save();

            if ($titleReport->status ==1) {
                $titles = $this->titleReport->all();
                foreach($titles as $item)
                {
                    if($item->id != $titleReport->id)
                    {
                        $item->update([
                            'status'=>0
                        ]);
                    }
                }
            }

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
        $titleReport = $this->titleReport->find($id)->delete();

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
        $setting = $this->setting->find(1);

        if ($setting) {

            $setting->fill([
                "placeholder" => $request->placeholder
            ])->save();

        }else {
            $setting = $this->setting->create([
                "placeholder" => $request->placeholder
            ]);
        }
        return back()->with('success', "Placeholder mis à jour avec succès  ! ");
    }

    public function report_store_footer(Request $request)
    {
        if (!getOnlineUser()->can('create-settings') ) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        if (!getOnlineUser()->can('edit-settings') ) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $setting = $this->setting->find(1);

        if ($setting) {

            $setting->fill([
                "footer" => $request->footer
            ])->save();

        }else {
            $setting = $this->setting->create([
                "footer" => $request->footer
            ]);
        }
        return back()->with('success', "Pied de page mis à jour avec succès  ! ");
    }

    public function app()
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $setting = $this->setting->find(1);
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

        $setting = $this->setting->find(1);

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
            $setting = $this->setting->create([
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

    public function invoice_index()
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // $app_name = config('app.name');
        // $setting = $this->setting->where('titre','like',$app_name)->first();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        $settingInvoice = SettingInvoice::find(1);
        return view('invoices.setting' , compact('settingInvoice'));
    }

    public function invoice_update(SettingInvoiceRequest $request)
    {
        if (!getOnlineUser()->can('view-settings')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        //dd($request);
        $data = [
            'ifu' => $request->ifu,
            'token' => $request->token,
            'status' => $request->status
        ];

        try {

            $settingInvoice = $this->settingInvoice->find(1);
            $settingInvoice->ifu = $data['ifu'];
            $settingInvoice->token = $data['token'];
            $settingInvoice->status = $request->status?1:0;
            $settingInvoice->save();

            return back()->with('success', "Mise à jour éffectué avec success ");
            //return response()->json(200);
            //return response()->json($request);
        } catch (\Throwable $ex) {
            return response()->json('error', "Échec de la mis à jour ! " . $ex->getMessage());
        }
    }
}
