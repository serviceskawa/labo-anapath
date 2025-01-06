<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Models\CashboxAdd;
use App\Models\CashboxDaily;
use App\Models\Invoice;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

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
        if (!getOnlineUser()->can('view-cashbox-dailies')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $cashboxDailys = CashboxDaily::latest()->get();
        // dd($cashboxDailys);

        $cashboxs = Cashbox::find(2);
        $cashboxtest = Cashbox::find(2);

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('cashbox_daily.index',compact('cashboxDailys','cashboxs','cashboxtest'));
    }

    public function print($id)
    {
        $item = CashboxDaily::find($id);
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('cashbox_daily.print',compact('item','setting'));
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
        if (!getOnlineUser()->can('create-cashbox-dailies')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        try {
            // dd('ok');
            $sf = Cashbox::find(2);
            if($sf->current_balance==0 || $sf->current_balance==null)
            {
                $new_current_balance = 0.0;
            }else{
            $new_current_balance = $sf->current_balance - $request->solde_ouverture;
            }

            $sf->update([
                'opening_balance' => $request->solde_ouverture,
                'current_balance' => $new_current_balance,
                'statut' => 1
            ]);


                try {
                    $cash = CashboxDaily::create([
                        'opening_balance' => $request->solde_ouverture,
                        'close_balance' => 0.0,
                        'cashbox_id' => $request->typecaisse,
                        'status' => 1,
                        'cash_calculated' => null,
                        'cash_confirmation' => null,
                        'cash_ecart' => null,
                        'mobile_money_calculated' => null,
                        'mobile_money_confirmation' => null,
                        'mobile_money_ecart' => null,
                        'cheque_calculated' => null,
                        'cheque_confirmation' => null,
                        'cheque_ecart' => null,
                        'virement_calculated' => null,
                        'virement_confirmation' => null,
                        'virement_ecart' => null,
                        'total_calculated' => null,
                        'total_confirmation' => null,
                        'total_ecart' => null,
                        'user_id' => auth()->user()->id,
                        'code' => generateCodeOpeningCashbox()
                    ]);
                } catch (\Throwable $th) {
                    dd($th);
                }

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


    public function detail_fermeture_caisse()
    {

        $closecashbox = CashboxDaily::where('status',1)->orderBy('updated_at','desc')->first();
        // dd($closecashbox);
        // ->whereRaw('Date(updated_at) = ?', [now()->toDateString()])

        if ($closecashbox) {
            // mobile money
            $mobilemoneysum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "MOBILEMONEY");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->sum('amount');

            $mobilemoneycount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "MOBILEMONEY");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->count();

            // Cheques
            $chequessum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "CHEQUES");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->sum('amount');

            $chequescount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "CHEQUES");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->count();

            // Especes
            $especessum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "ESPECES");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->sum('amount');

            $especescount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "ESPECES");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->count();


            // Virement
            $virementsum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "VIREMENT");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->sum('amount');

            $virementcount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "VIREMENT");
            })
            // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->where('updated_at','>=', $closecashbox->updated_at)
            ->count();
        } else {
            // mobile money
            $mobilemoneysum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "MOBILEMONEY");
            })
            ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->sum('amount');

            $mobilemoneycount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "MOBILEMONEY");
            })
            ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->count();

            // Cheques
            $chequessum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "CHEQUES");
            })->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->sum('amount');

            $chequescount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "CHEQUES");
            })->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->count();

            // Especes
            $especessum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "ESPECES");
            })->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->sum('amount');

            $especescount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "ESPECES");
            })->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->count();


            // Virement
            $virementsum = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "VIREMENT");
            })->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->sum('amount');

            $virementcount = CashboxAdd::where('cashbox_id', 2)->whereHas('invoice', function ($query) {
                $query->where('payment', '=', "VIREMENT");
            })->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            // ->where('updated_at','>', $closecashbox->updated_at)
            ->count();
        }


        $open_cash = CashboxDaily::latest()->first();
        return view('cashbox_daily.fermeture',compact('open_cash','mobilemoneysum','mobilemoneycount','virementsum','virementcount','especescount','especessum','chequescount','chequessum'));
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
        if (!getOnlineUser()->can('edit-cashbox-dailies')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // dd($request);
        try {
        $sf = CashboxDaily::latest()->first();

        if ($sf->user_id != Auth::user()->id) {
            return back()->with('error', "Vous n'êtes pas autorisé à fermer la caisse");
        }

        // $serach
            $sf->update([
                    'close_balance' => $request->close_balance,
                    'status' => $request->status,
                    'cash_calculated' => $request->cash_calculated ? $request->cash_calculated : 0.0,
                    'cash_confirmation' => $request->cash_confirmation ? $request->cash_confirmation : 0.0,
                    'cash_ecart' => $request->cash_ecart ? $request->cash_ecart : 0.0,
                    'mobile_money_calculated' => $request->mobile_money_calculated ? $request->mobile_money_calculated : 0.0,
                    'mobile_money_confirmation' => $request->mobile_money_confirmation ? $request->mobile_money_confirmation : 0.0,
                    'mobile_money_ecart' => $request->mobile_money_ecart ? $request->mobile_money_ecart : 0.0,
                    'cheque_calculated' => $request->cheque_calculated ? $request->cheque_calculated : 0.0,
                    'cheque_confirmation' => $request->cheque_confirmation ? $request->cheque_confirmation : 0.0,
                    'cheque_ecart' => $request->cheque_ecart ? $request->cheque_ecart : 0.0,
                    'virement_calculated' => $request->virement_calculated ? $request->virement_calculated : 0.0,
                    'virement_confirmation' => $request->virement_confirmation ? $request->virement_confirmation : 0.0,
                    'virement_ecart' => $request->virement_ecart ? $request->virement_ecart : 0.0,
                    'total_calculated' => $request->total_calculated ? $request->total_calculated : 0.0,
                    'total_confirmation' => $request->total_confirmation_point ? $request->total_confirmation_point : 0.0,
                    'total_ecart' => $request->total_ecart_point ? $request->total_ecart_point : 0.0,
                    'user_id' => auth()->user()->id,
            ]);

            // $cashb = Cashbox::where('id', 2)->get();

            $lastvalCashbox = Cashbox::find(2);
            // dd($lastvalCashbox);

            // on met a jour le solde total de la caisse : montant actuel + solde d'ouverture + ecart
                $result = $lastvalCashbox->current_balance + $lastvalCashbox->opening_balance + $sf->total_ecart;
                // dd($result);
            $lastvalCashbox->update([
                    'current_balance' => $result,
                    'opening_balance' => 0,
                    'statut' => 0,
            ]);

                return redirect(route('daily.index'))->with('success', " Opération effectuée avec succès  ! ");
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
