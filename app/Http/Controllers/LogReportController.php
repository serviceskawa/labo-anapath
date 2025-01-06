<?php

namespace App\Http\Controllers;

use App\Models\LogReport;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class LogReportController extends Controller
{
    protected $logReports;
    protected $user;
    protected $setting;

    public function __construct(LogReport $logReports, User $user, Setting $setting){
        $this->logReports = $logReports;
        $this->setting = $setting;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-users')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $logs = $this->logReports->latest()->get();
        $users = $this->user->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('logReport.index', compact('logs'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->logReports->find($id);
        return response()->json($data);
    }

    public function getuser($id)
    {
        // if (!getOnlineUser()->can('view-settings')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = $this->user->find($id);
        return response()->json($data);
    }

}
