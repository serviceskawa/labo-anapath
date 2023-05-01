<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Setting;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $patients;
    protected $setting;
    protected $appointment;
    protected $doctors;
    protected $consultation;



    public function __construct(Patient $patients, Setting $setting, Appointment $appointment, Doctor $doctors, Consultation $consultation)
    {
        $this->patients = $patients;
        $this->setting = $setting;
        $this->doctors = $doctors;
        $this->appointment = $appointment;
        $this->consultation = $consultation;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Récupérer tous les patients
        $patients = $this->patients->all();

        //Les utilisatuers au=yant le rôle docteur
        $doctors = getUsersByRole("docteur");

        //Dans la table Setting récupérer le premier enrégistrement
        $setting = $this->setting->find(1);

        //charger le nom de l'application en fonction des données récupérées dans la table Setting
        config(['app.name' => $setting->titre]);
        return view('Appointment.index', compact('patients', 'doctors'));
    }

    public function getAppointments()
    {
        //Récupérer les données de la table Appointments avec les données des tables Doctor, Patient, 'doctor_interne' associées
        $appointmentData = $this->appointment->getWithNewAppointments()->get();

        $events = [];
        foreach ($appointmentData as $key => $value) {
            $events[$key] = [
                "title" => $value['doctor_interne'] != null ? "RDV " . $value['doctor_interne']->firstname . ' ' . $value['doctor_interne']->lastname : '', // change doctor_id en  user_id pour la reference dses docteurs internes
                "id" => $value['id'],
                "start" => $value['date'],
                "doctorId" => $value['user_id'],
                "doctorName" => $value['doctor_interne'] != null ? $value['doctor_interne']->firstname . ' ' . $value['doctor_interne']->lastname : '',
                "className" => randColor($value['priority']),
            ];
        }
        return response()->json($events);
    }

    public function getAppointmentsById(Request $request)
    {
        //Récupérer une donnée de la table avec son ID et les données Doctor et Doctor interne correspondant
        $appointmentData = $this->appointment->getWithNewAppointments()->whereId($request->id)->first();

        return response()->json($appointmentData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentRequest $request)
    {
        //insertion de nouvelles données dans la base de données en vérifiant si les données respectent les conditions de validation

        $data = [
            'doctorId' => $request->doctorId,
            'patientId' => $request->patientId,
            'time' => $request->time,
            'message' => $request->message,
            'priority' => $request->priority,
        ];

        $appointmentData = $this->appointment->create([
            "user_id" => $data['doctorId'], // change doctor_id en  user_id pour la reference dses docteurs internes
            "patient_id" => $data['patientId'],
            "priority" => $data['priority'],
            "message" => $data['message'],
            "status" => "pending",
            "date" =>  convertDateTime($data['time']),
        ]);
        return response()->json($appointmentData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $patients = $this->patients->all();

        $doctors = $this->doctors->all();

        $appointment = $this->appointment->findorfail($id);

        if (empty($appointment)) {
            return back()->with('error', "Une Erreur est survenue. Ce rendez-vous n'hesite pas");
        }

        //Dans la table Setting récupérer le premier enrégistrement
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('Appointment.show', compact('Appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentRequest $request)
    {
        //Mis à jour des données
        
        $data = [
            'doctorId' => $request->doctorId,
            'patientId' => $request->patientId,
            'time' => $request->time,
            'message' => $request->message,
            'priority' => $request->priority,
        ];
        // $data = $this->validate($request,[
        //     'doctorId' => 'nullable',
        //     'patientId' => 'required',
        //     'time' => 'required',
        //     'message' => 'nullable',
        //     'priority' => 'required',
        // ]);


        $appointment = $this->appointment->findorfail($request->id);

        if (empty($appointment)) {
            return back()->with('error', "Une Erreur est survenue. Ce rendez-vous n'existe pas");
        }

        try {
            $appointment = $this->appointment->find($request->id);
            $appointment->user_id = $data['doctorId'];
            $appointment->patient_id = $data['patientId'];
            $appointment->message = $data['message'];
            $appointment->priority = $data['priority'];
            $appointment->date =  convertDateTime($data['time']);
            $appointment->save();

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

        Appointment::whereId($id)->delete();
        return response()->json();
    }

    public function createConsultation($appointmentId)
    {

        $appointment = $this->appointment->findorfail($appointmentId);

        if (empty($appointment)) {
            return back()->with('error', "Une Erreur est survenue. Ce rendez-vous n'existe pas");
        }

        // dd($request);
        $latest = $this->consultation->latest('id')->first();
        $code = sprintf('%04d', empty($latest->id) ? "1" : $latest->id);

        try {
            $consultation = $this->consultation->updateOrInsert(
                ["appointment_id" => $appointment->id,],
                [
                    "code" => "CON" . $code,
                    "patient_id" => $appointment->patient_id,
                    "doctor_id" => $appointment->doctor_id,
                    "date" => convertDateTime($appointment->date),

                ]
            );

            $consultation = $appointment->consultation;

            return redirect()->route('consultation.show', $consultation->id)->with('success', "Consultation ajouté avec succès");
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            dd($error);
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }
}
