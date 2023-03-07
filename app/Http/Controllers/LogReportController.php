<?php

namespace App\Http\Controllers;

use App\Models\LogReport;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class LogReportController extends Controller
{
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
        $logs = LogReport::latest()->get();
        $users = User::all();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('logReport.index', compact('logs'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if (!getOnlineUser()->can('view-settings')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = LogReport::find($id);
        return response()->json($data);
    }

    public function getuser($id)
    {
        // if (!getOnlineUser()->can('view-settings')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = User::find($id);
        return response()->json($data);
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
}
