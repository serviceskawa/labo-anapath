<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\PrestationOrder;
use App\Models\Setting;

class PrestationsOrderrController extends Controller
{
    protected $patients;
    protected $prestations;
    protected $prestationOrders;
    protected $setting;

    public function __construct(Patient $patients, Prestation $prestations, PrestationOrder $prestationOrders, Setting $setting)
    {
        $this->middleware('auth');

        $this->patients = $patients;
        $this->prestations = $prestations;
        $this->prestationOrders = $prestationOrders;
        $this->setting = $setting;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $patients = $this->patients->all();
        $prestations = $this->prestations->all();
        $prestationOrders = $this->prestationOrders->latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('prestationsOrder.index', compact(['patients', 'prestations', 'prestationOrders']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $this->validate($request, [
            'patient_id' => 'required',
            'prestation_id' => 'required',
            'total' => 'required'
        ]);

        try {
            $this->prestationOrders->create($data);
            return back()->with('success', "Nouvelle demande de prestation enregistrée ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->prestationOrders->find($id);
        $patient = $this->patients->find($data->patient_id);

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

        $data = $this->validate($request, [
            'id' => 'required',
            'patient' => 'required',
            'prestation_id' => 'required',
            'total' => 'required',
            'status' => 'required'
        ]);


        try {

            $prestationOrder = $this->prestationOrders->find($data['id']);
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

        $prestationOrder = $this->prestationOrders->find($id)->delete();

        if ($prestationOrder) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }

    public function getPrestationOrder(Request $request)
    {

        $prestation = $this->prestations->find($request->prestationId);

        return response()->json(["total" => $prestation->price]);
    }

    public function getDetailsPrestation(Request $request)
    {
        if (!getOnlineUser()->can('view-test-orders')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $prestation = $this->prestations->find($id);
        return response()->json($prestation);
    }
}
