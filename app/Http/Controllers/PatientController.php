<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientRequest;
use App\Models\Consultation;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PrestationOrder;
use App\Models\Setting;
use App\Models\TestOrder;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    protected $patients;
    protected $setting;
    protected $testOrders;
    protected $invoices;

    public function __construct(Patient $patients, Setting $setting, Invoice $invoices, TestOrder $testOrders)
    {
        $this->patients  = $patients;
        $this->setting = $setting;
        $this->invoices = $invoices;
        $this->testOrders = $testOrders;
    }
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
        $patients = $this->patients->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('patients.index', compact(['patients']));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PatientRequest $request)
    {
        if (!getOnlineUser()->can('create-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'code' => $request->code,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'telephone1' => $request->telephone1,
            'telephone2' => $request->telephone2,
            'adresse' => $request->adresse,
            'genre' => $request->genre,
            'year_or_month' => $request->year_or_month,
            'age' => $request->age,
            'langue' => $request->langue,
            'profession' => $request->profession,
            'birthday' => $request->birthday
        ];


        try {
            $this->patients->create($data);
            return back()->with('success', "Un patient enregistré ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function storePatient(PatientRequest $request)
    {
        if (!getOnlineUser()->can('create-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'code' => $request->code,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'telephone1' => $request->telephone1,
            'age' => $request->age,
            'year_or_month' => $request->year_or_month,
            'genre' => $request->genre,
            'langue' => $request->langue,
        ];

        try {
            $patient = $this->patients->create($data);

            return response()->json($patient, 200);
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
        if (!getOnlineUser()->can('edit-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->patients->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PatientRequest $request)
    {
        if (!getOnlineUser()->can('edit-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'id' => $request->id,
            'code' => $request->code,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'telephone1' => $request->telephone1,
            'telephone2' => $request->telephone2,
            'adresse' => $request->adresse,
            'genre' => $request->genre,
            'year_or_month' => $request->year_or_month,
            'age' => $request->age,
            'langue' => $request->langue,
            'profession' => $request->profession,
            'birthday' => $request->birthday
        ];


        try {

            $patient = $this->patients->find($data['id']);
            $patient->code = $data['code'];
            $patient->firstname = $data['firstname'];
            $patient->lastname = $data['lastname'];
            $patient->genre = $data['genre'];
            $patient->telephone1 = $data['telephone1'];
            $patient->telephone2 = $data['telephone2'];
            $patient->adresse = $data['adresse'];
            $patient->age = $data['age'];
            $patient->year_or_month = $data['year_or_month'];
            $patient->profession = $data['profession'];
            $patient->langue = $data['langue'];
            $patient->save();

            // rechercher le nom du patient dans le invoices et corrigé
            $invoice = Invoice::where('patient_id', $data['id'])->first();
            if ($invoice) {
                $invoice->client_name = $data['firstname'] . ' ' . $data['lastname'];
                $invoice->client_address = $data['adresse'];
                $invoice->save();
            }


            return back()->with('success', "Un patient mis à jour ! ");
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
        if (!getOnlineUser()->can('delete-patients')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $patient = $this->patients->find($id)->delete();

        if ($patient) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }

    public function getPatients()
    {
        $patients = $this->patients->orderBy('id', 'desc')->get();
        return response()->json($patients);
    }

    public function profil($id)
    {


        $invoices = $this->invoices->where('patient_id', '=', $id)->latest()->get();
        $patient = $this->patients->find($id);

        $total = $this->invoices->where('patient_id', '=', $id)->sum('total');

        $nopaye = $this->invoices->where(['patient_id' => $id, 'paid' => 0])->where(['status_invoice' => 0])->sum('total');

        $annule = $this->invoices->where(['patient_id' => $id, 'paid' => 1])->where(['status_invoice' => 1])->sum('total');

        $paye = $this->invoices->where(['patient_id' => $id, 'paid' => 1])->where(['status_invoice' => 0])->sum('total') - $annule;

        $testorders = $this->testOrders->where('patient_id', '=', $id)->latest()->get();

        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('patients.profil', compact(['invoices', 'testorders', 'patient', 'total', 'nopaye', 'paye']));
    }
}
