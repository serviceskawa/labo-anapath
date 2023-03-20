<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Setting;
use Illuminate\Http\Request;

class AppointementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::all();
        $doctors = getUsersByRole("docteur");
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('appointement.index', compact('patients', 'doctors'));
    }

    public function getAppointements()
    {
        $data = Appointment::with(['doctor', 'patient', 'doctor_interne'])->get();
        $events = [];
        foreach ($data as $key => $value) {
            $events[$key] = [
                "title" => $value['doctor_interne'] !=null ? "RDV " . $value['doctor_interne']->firstname . ' ' . $value['doctor_interne']->lastname : '', // change doctor_id en  user_id pour la reference dses docteurs internes
                "id" => $value['id'],
                "start" => $value['date'],
                "doctorId" => $value['user_id'],
                "doctorName" => $value['doctor_interne'] !=null ? $value['doctor_interne']->firstname . ' ' . $value['doctor_interne']->lastname : '',
                "className" => randColor($value['priority']),
            ];
        }
        return response()->json($events);
    }

    public function getAppointementsById(Request $request)
    {
        $data = Appointment::with(['doctor', 'patient', 'doctor_interne'])->whereId($request->id)->first();

        return response()->json($data);
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
            'doctorId' => 'nullable',
            'patientId' => 'required',
            'time' => 'required',
            'message' => 'nullable',
            'priority' => 'required',
        ]);

        Appointment::create([
            "user_id" => $data['doctorId']!=null ? $data['doctorId'] : '', // change doctor_id en  user_id pour la reference dses docteurs internes
            "patient_id" => $data['patientId'],
            "priority" => $data['priority'],
            "message" => $data['message'],
            "status" => "pending",
            "date" =>  convertDateTime($data['time']),
        ]);
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $appointement = Appointment::findorfail($id);

        if (empty($appointement)) {
            return back()->with('error', "Une Erreur est survenue. Ce rendez-vous n'hesite pas");
        }

        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);

        return view('appointement.show', compact('appointement', 'patients', 'doctors'));
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

        $data = $this->validate($request, [
            'patient_id' => 'required',
            'doctor_id' => 'nullable',
            'date' => 'required',
            'message' => 'nullable',
            'priority' => 'required',
        ]);
        $appointement = Appointment::findorfail($id);

        if (empty($appointement)) {
            return back()->with('error', "Une Erreur est survenue. Ce rendez-vous n'existe pas");
        }

        try {
            $appointement = Appointment::find($id);
            $appointement->user_id = $data['doctor_id'] != null ? $data['doctor_id'] : '';
            $appointement->patient_id = $data['patient_id'];
            $appointement->message = $data['message'];
            $appointement->priority = $data['priority'];
            $appointement->date =  convertDateTime($data['date']);
            $appointement->save();

            return response()->json($data);
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            // dd($error);
            return back()->with('error', "Échec de l'enregistrement ! ");
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
        // if (!getOnlineUser()->can('delete-hospitals')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        Appointment::whereId($id)->delete();
        return response()->json();
        // return redirect()->route('appointement.index')->with('success', "Rendez-vous supprimé ! ");
    }

    public function createConsultation($appointementId)
    {

        $appointement = Appointment::findorfail($appointementId);

        if (empty($appointement)) {
            return back()->with('error', "Une Erreur est survenue. Ce rendez-vous n'existe pas");
        }

        // dd($request);
        $latest = Consultation::orderBy('id', 'DESC')->first();
        $code = sprintf('%04d', empty($latest->id) ? "1" : $latest->id);

        try {
            $consultation = Consultation::updateOrInsert(
                ["appointment_id" => $appointement->id,],
                [
                    "code" => "CON" . $code,
                    "patient_id" => $appointement->patient_id,
                    "doctor_id" => $appointement->doctor_id,
                    "date" => convertDateTime($appointement->date),

                ]
            );

            $consultation = $appointement->consultation;

            return redirect()->route('consultation.show', $consultation->id)->with('success', "Consultation ajouté avec succès");
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            dd($error);
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }
}
