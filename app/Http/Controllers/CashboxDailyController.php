<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxDaily;
use App\Models\Setting;
use Illuminate\Http\Request;

class CashboxDailyController extends Controller
{
    protected $cashboxDaily;
    protected $setting;

    public function __construct(CashboxDaily $cashboxDaily, Setting $setting){
        $this->cashboxDaily = $cashboxDaily;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-expenses')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        
        $cashboxDailys = $this->cashboxDaily->latest()->get();
        $cashboxs = Cashbox::latest()->first();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('cashbox_daily.index',compact(['cashboxDailys','cashboxs']));   
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
        // if (!getOnlineUser()->can('create-expenses')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        try {
                CashboxDaily::create([
                    'opening_balance' => $request->solde,
                    'close_balance' => 0,
                    'cashbox_id' => $request->typecaisse,
                    'status' => $request->status,
                ]);

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CashboxDaily  $cashboxDaily
     * @return \Illuminate\Http\Response
     */
    public function show(CashboxDaily $cashboxDaily)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CashboxDaily  $cashboxDaily
     * @return \Illuminate\Http\Response
     */
    public function edit(CashboxDaily $cashboxDaily)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CashboxDaily  $cashboxDaily
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CashboxDaily $cashboxDaily)
    {
        try {
        $sf = Cashbox::find(1);
        // dd($sf->current_balance);
            $cashboxDaily->update([
                    'close_balance' => $sf->current_balance,
                    'status' => $request->status,
                ]);

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CashboxDaily  $cashboxDaily
     * @return \Illuminate\Http\Response
     */
    public function destroy(CashboxDaily $cashboxDaily)
    {
        //
    }
}