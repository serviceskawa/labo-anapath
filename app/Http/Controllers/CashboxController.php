<?php

namespace App\Http\Controllers;

use App\Models\Cashbox;
use App\Http\Requests\StoreCashboxRequest;
use App\Http\Requests\UpdateCashboxRequest;
use App\Models\Bank;
use App\Models\CashboxAdd;
use App\Models\CashboxDaily;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashboxController extends Controller
{

    protected $setting;
    protected $cashadd;
    protected $banks;
    protected $cash;

    public function __construct(Setting $setting, CashboxAdd $cashadd, Cashbox $cash, Bank $banks)
    {
        $this->setting = $setting;
        $this->cashadd = $cashadd;
        $this->banks = $banks;
        $this->cash = $cash;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-cashboxes')) {
            return back()->with('error', "Vous n\'êtes pas autorisé");
        }

        // $cashadds = $this->cashadd->where('cashbox_id',2)->latest()->get();
        // $totalToday = $this->cash->find(2)->current_balance;
        // $banks = $this->banks->all();
        // $cashboxDailys = CashboxDaily::latest()->get();
        // $cashboxs = Cashbox::find(2);
        // $cashboxtest = Cashbox::find(2);
        $cashbox = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type','vente')->first();

        $cashadds = $this->cashadd->where('cashbox_id', $cashbox->id)->latest()->get();
        $totalToday = $this->cash->find($cashbox->id)->current_balance;
        $banks = $this->banks->all();
        $cashboxDailys = CashboxDaily::latest()->get();
        $cashboxs = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type','vente')->first();
        $cashboxtest = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type','vente')->first();

        // Point en temps reel sur le cashboxadd
        $entree = CashboxAdd::where('cashbox_id', $cashbox->id)
            ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->sum('amount');
        // $entree = CashboxAdd::where('type', 'vente')
        //     ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
        //     ->sum('amount');

        // $sortie = CashboxAdd::where('cashbox_id', 1)
        // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
        // ->sum('amount');
        // Total des entrees et sorties de la journee
        $sortie = 0;
        $total = $entree + $sortie;

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('cashbox.vente.index', compact(['sortie', 'entree', 'cashadds', 'totalToday', 'banks', 'cashboxDailys', 'cashboxs', 'cashboxtest']));
    }

    public function index_depense()
    {
        if (!getOnlineUser()->can('view-cashboxes')) {
            return back()->with('error', "Vous n\'êtes pas autorisé");
        }
        $cashboxDepense = Cashbox::where('branch_id', session()->get('selected_branch_id'))->where('type','depense')->first();

        // Point en temps reel sur le cashboxadd
        $sortie = CashboxAdd::where('cashbox_id',$cashboxDepense->id)
            ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
            ->sum('amount');

        // $sortie = CashboxAdd::where('cashbox_id', 1)
        // ->whereRaw('DATE(updated_at) = ?', [now()->toDateString()])
        // ->sum('amount');
        $entree = 0;
        // Total des entrees et sorties de la journee
        $total = $entree + $sortie;

        $cashadds = $this->cashadd->where('cashbox_id',$cashboxDepense->id)->latest()->get();
        $totalToday = $this->cash->find($cashboxDepense->id)->current_balance;
        $banks = $this->banks->all();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('cashbox.depense.index', compact(['sortie', 'entree', 'cashadds', 'totalToday', 'banks']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCashboxRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-cashboxes')) {
            return back()->with('error', "vous n'êtes pas autorisé");
        }

        $cashboxAddData = [
            'bank_id' => $request->bank_id,
            'cheque_number' => $request->cheque_number,
            'cashbox_id' => 1,
            'amount' => $request->amount,
            'date' => $request->date,
            'user_id' => Auth::user()->id,
            'description' => $request->description
        ];

        try {

            // $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
            $cashboxAdd = $this->cashadd->create([
                'amount' => $cashboxAddData['amount'],
                'bank_id' => $cashboxAddData['bank_id'],
                'cheque_number' => $cashboxAddData['cheque_number'],
                'date' => $cashboxAddData['date'],
                'cashbox_id' => $cashboxAddData['cashbox_id'],
                'description' => $cashboxAddData['description'],
                'user_id' => $cashboxAddData['user_id']
            ]);

            $cash = $this->cash->find(1);
            $cash->current_balance += $cashboxAddData['amount'];
            $cash->save();
            return back()->with('success', "Les informations de la caisse de vente ont été mis à jour ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function store_bank(Request $request)
    {
        if (!getOnlineUser()->can('create-banks')) {
            return back()->with('error', "vous n'êtes pas autorisé");
        }

        $cashboxAddData = [
            'bank_id' => $request->bank_id,
            'cashbox_id' => 2,
            'amount' => $request->amount,
            'attachement' => $request->file('bank_file')->store('bank_deposit', 'public'),
            'date' => $request->date,
            'description' => $request->description ? $request->description : '',
            'user_id' => Auth::user()->id
        ];

        try {
            $cash = $this->cash->find(2);

            if ($cash->current_balance > $cashboxAddData['amount']) {
                // $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
                $cashboxAdd = $this->cashadd->create([
                    'amount' => $cashboxAddData['amount'],
                    'bank_id' => $cashboxAddData['bank_id'],
                    'date' => $cashboxAddData['date'],
                    'attachement' => $cashboxAddData['attachement'],
                    'description' => $cashboxAddData['description'],
                    'cashbox_id' => $cashboxAddData['cashbox_id'],
                    'user_id' => $cashboxAddData['user_id']
                ]);

                $cash->current_balance -= $cashboxAddData['amount'];
                $cash->save();
                return back()->with('success', "Les informations de la caisse de vente ont été mis à jour ! ");
            } else {
                return back()->with('error', "Le montant du dépôt ne peut pas être supérieur au montant de la caisse");
            }
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Cashbox  $cashbox
     * @return \Illuminate\Http\Response
     */
    public function show(Cashbox $cashbox)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Cashbox  $cashbox
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-cashboxes')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $cashData = $this->cashadd->find($id);
        $invoice = $cashData->invoice ? ($cashData->invoice->order ? $cashData->invoice->order->code : '') : '';
        $bank = $cashData->bank ? $cashData->bank->name : '';
        return response()->json(['data' => $cashData, 'invoice' => $invoice, 'bank' => $bank]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCashboxRequest  $request
     * @param  \App\Models\Cashbox  $cashbox
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-cashboxes')) {
            return back()->with('error', "Vous n\'êtes pas autorisé");
        }

        $cashboxAddData = [
            'id' => $request->id,
            'bank_id' => $request->bank_id,
            'invoice_id' => $request->invoice_id,
            'cheque_number' => $request->cheque_number,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description
        ];


        try {

            $cashboxAdd = CashboxAdd::find($cashboxAddData['id']);
            $cashboxAdd->amount = $cashboxAddData['amount'];
            $cashboxAdd->invoice_id = $cashboxAddData['invoice_id'];
            $cashboxAdd->bank_id = $cashboxAddData['bank_id'];
            $cashboxAdd->cheque_number = $cashboxAddData['cheque_number'];
            $cashboxAdd->date = $cashboxAddData['date'];
            $cashboxAdd->description = $cashboxAddData['description'];
            $cashboxAdd->save();

            return back()->with('success', "Les informations de la caisse de vente ont été mis à jour ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Cashbox  $cashbox
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-cashboxes')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $add = $this->cashadd->find($id);
        $cash = $this->cash->find($add->cashbox_id);
        try {
            $this->cashadd->find($id)->delete();
            $cash->current_balance -= $add->amount;
            $cash->save();
            return back()->with('success', "    Un élement a été supprimé ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Impossible de supprimer cet élément !  Celui-ci est lié à d'autres éléments. Pour effectuer cette suppression, vous devez d'abord supprimer ou mettre à jour les éléments liés dans d'autres tables.");
        }
    }
}
