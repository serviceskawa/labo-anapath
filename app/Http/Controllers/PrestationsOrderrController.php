<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\PrestationOrder;
use App\Models\Setting;

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
        // if (!getOnlineUser()->can('view-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $patients = Patient::all();
        $prestations = Prestation::all();
        $prestationOrders = PrestationOrder::orderBy('created_at', 'DESC')->get();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);    
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

        $patients = Patient::all();
        $prestations = Prestation::all();

        $data = $this->validate($request, [
            'patient_id' => 'required',
            'prestation_id' => 'required',
            'total' => 'required'
        ]);

        try {
            PrestationOrder::create($data);
            return back()->with('success', "Nouvelle demande de prestation enregistrée ! ");
        } catch (\Throwable $ex) {
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
        // if (!getOnlineUser()->can('edit-patients')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = PrestationORder::find($id);
        $patient = Patient::find($data->patient_id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('edit-patients')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = $this->validate($request, [
            'id' => 'required',
            'patient' => 'required',
            'prestation_id' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);


        try {

            $prestationOrder = PrestationOrder::find($data['id']);
            $prestationOrder->patient_id = $data['patient'];
            $prestationOrder->prestation_id = $data['prestation_id'];
            $prestationOrder->total = $data['total'];
            $prestationOrder->status = $data['status'];
            $prestationOrder->save();

            return back()->with('success', "Un demande mis à jour ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-patients')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $prestationOrder = PrestationOrder::find($id)->delete();

        if ($prestationOrder) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }

    public function getPrestationOrder(Request $request)
    {
        // if (!getOnlineUser()->can('view-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        //dd($request->prestationId);
        $prestation = Prestation::find($request->prestationId);

        return response()->json(["total" => $prestation->price]);
    }

    public function getDetailsPrestation(Request $request)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        // dd($request);

        $prestation = Prestation::find($id);
        return response()->json($prestation);
    }
}
