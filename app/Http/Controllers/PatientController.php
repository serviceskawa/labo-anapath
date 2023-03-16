<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PrestationOrder;
use App\Models\Setting;
use App\Models\TestOrder;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $patients = Patient::latest()->get();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('patients.index',compact(['patients']));

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
        if (!getOnlineUser()->can('create-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'code' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'telephone1' => 'required',
            'telephone1' => 'nullable',
            'adresse' => 'nullable',
            'genre' => 'required',
            'year_or_month' => 'required',
            'age' => 'required | integer',
            'profession' => 'nullable',
            'birthday' => 'nullable||date'
        ]);


        try {
            Patient::create($data);
            return back()->with('success', "Un patient enregistré ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    public function storePatient(Request $request)
    {
        if (!getOnlineUser()->can('create-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'code' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'telephone1' => 'required',
            'age' => 'required',
            'year_or_month' => 'required',
            'genre' => 'required',
        ]);

        try {
            $patient = Patient::create($data);

            return response()->json($patient, 200);

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
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
        if (!getOnlineUser()->can('edit-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = Patient::find($id);
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
        if (!getOnlineUser()->can('edit-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data=$this->validate($request, [
            'id2' => 'required',
            'code2' => 'required',
            'firstname2' => 'required',
            'lastname2' => 'required',
            'telephone1_2' => 'required',
            'telephone2_2' => 'nullable',
            'adresse2' => 'nullable',
            'genre2' => 'required',
            'age2' => 'required | integer',
            'year_or_month2' => 'required',
            'profession2' => 'nullable'
        ]);


        try {

            $patient = Patient::find($data['id2']);
            $patient->code = $data['code2'];
            $patient->firstname = $data['firstname2'];
            $patient->lastname = $data['lastname2'];
            $patient->genre = $data['genre2'];
            $patient->telephone1 = $data['telephone1_2'];
            $patient->telephone2 = $data['telephone2_2'];
            $patient->adresse = $data['adresse2'];
            $patient->age = $data['age2'];
            $patient->year_or_month = $data['year_or_month2'];
            $patient->profession = $data['profession2'];

            $patient->save();

            return back()->with('success', "Un patient mis à jour ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
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
        if (!getOnlineUser()->can('delete-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $patient = Patient::find($id)->delete();

        if ($patient) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }

    public function getPatients()
    {
        $patients = Patient::orderBy('id','desc')->get();
        return response()->json($patients);
    }

    public function profil($id)
    {

        $invoices =Invoice::where('patient_id','=',$id)->latest()->get();

        $patient = Patient::find($id);

        $testorders =TestOrder::where('patient_id','=',$id)->latest()->get();

        $consultations = Consultation::where('patient_id','=',$id)->latest()->get();

        $prestationOrders = PrestationOrder::where('patient_id','=',$id)->latest()->get();


        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('patients.profil', compact(['invoices', 'testorders', 'consultations', 'prestationOrders', 'patient']));
    }
}
