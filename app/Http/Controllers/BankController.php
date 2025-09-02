<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Http\Requests\StoreBankRequest;
use App\Http\Requests\UpdateBankRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class BankController extends Controller
{

    protected $setting;
    protected $banks;
    public function __construct(Setting $setting, Bank $banks){
        $this->setting = $setting;
        $this->banks = $banks;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $banks = $this->banks->latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('bank.index',compact(['banks']));
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
     * @param  \App\Http\Requests\StoreBankRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // if (!getOnlineUser()->can('create-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }


        // Récupérer les données saisir par l'utilisateur et qui respectent les conditions
        $bankData = [
            'name' => $request->name,
            'account_number' => $request->account_number,
            'description' => $request->description
        ];


        // insérer les données dans la base de données
        try {
            Bank::create($bankData);
            return back()->with('success', "Une banque enregistrée ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!getOnlineUser()->can('edit-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $bankData = $this->banks->find($id);
        return response()->json($bankData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateBankRequest  $request
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('edit-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $bankData = [
            'id' => $request->id,
            'name' => $request->name,
            'account_number' => $request->account_number,
            'description' => $request->description
        ];


        try {

            $bank = Bank::find($bankData['id']);
            $bank->name = $bankData['name'];
            $bank->account_number = $bankData['account_number'];
            $bank->description = $bankData['description'];
            $bank->save();

            return back()->with('success', "Les informations d'une banque ont été mis à jour ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $this->banks->find($id)->delete();
        return back()->with('success', "Un élement a été supprimé ! ");
    }
}
