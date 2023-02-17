<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\PrestationOrder;

class PrestationsOrderrController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-demandes-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $patients = Patient::all();
        $prestations = Prestation::all();
        $prestationOrders = PrestationOrder::all();

        return view('prestationsOrder.index', compact(['patients', 'prestations', 'prestationOrders']));
        
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
        if (!getOnlineUser()->can('create-contrats')) {
                    return back()->with('error', "Vous n'êtes pas autorisé");
                }
        $patients = Patient::all();
        $prestations = Prestation::all();


        

        $data = $this->validate($request,[
            'patient_id' =>'required',
            'prestation_id' =>'required',
            'price' =>'required'
        ]);

        try {
            $prestationOrder = new PrestationOrder();
            $prestationOrder->patients_id = $data['patient_id'];
            $prestationOrder->prestations_id = $data['prestation_id'];
            $prestationOrder->total = $data['price'];
            $prestationOrder->status = 'new';
            $prestationOrder->save();

            $prestationOrders = PrestationOrder::all();

        return view('prestationsOrder.index', compact(['patients', 'prestations', 'prestationOrders']))->with('success', "Demande enregistré avec succès ! ");
        } catch (\Throwable$ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prestationOrderSelected = PrestationORder::find($id);
        dd($prestationOrderSelected);
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

    public function getPrestationOrder(Request $request)
    {
        if (!getOnlineUser()->can('view-demandes-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        //dd($request->prestationId);
        $prestation = Prestation::find($request->prestationId);

        return response()->json(["price"=>$prestation->price]);
    }

    public function getDetailsPrestation(Request $request)
    {
        if (!getOnlineUser()->can('view-demandes-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        dd($request);

        $prestation = Prestation::find($id);
        return response()->json($prestation);
    }
}
