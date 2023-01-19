<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TypeConsultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{

    public function index()
    {
        return view('consultation.index');
    }

    public function getConsultations()
    {
        $data = Consultation::with(['doctor', 'patient', 'type'])->get();

        foreach ($data as $key => $value) {
            $events[$key] = [
                "title" => "RDV " . $value['doctor']->name,
                "id" => $value['id'],
                "start" => $value['date'],
                "doctorId" => $value['doctor_id'],
                "doctorName" => $value['doctor']->name,
                "className" => randColorStatus($value['status']),
            ];
        }
        return response()->json($events);
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $types = TypeConsultation::all();
        return view('consultation.create', compact('patients', 'doctors', 'types'));
    }
    public function show($id)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $types = TypeConsultation::all();

        $consultation = Consultation::findOrFail($id);

        if (empty($consultation)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }

        return view('consultation.show', compact('patients', 'doctors', 'types', 'consultation'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'type_id' => 'required|exists:type_consultations,id',
            'status' => 'required',
            'date' => 'required',
            'motif' => 'required',
            'fees' => 'required',
            'anamnese' => 'nullable',
            'examen_physique' => 'nullable',
            'diagnostic' => 'nullable',
            'antecedent' => 'nullable',
            'payement_mode' => 'nullable',
            'next_appointment' => 'nullable',
            'id' => 'nullable',
        ]);

        // dd($request);

        try {
            Consultation::Create(
                [
                    "patient_id" => $data['patient_id'],
                    "doctor_id" => $data['doctor_id'],
                    "type_consultation_id" => $data['type_id'],
                    "status" => $data['status'],
                    "date" => convertDateTime($data['date']),
                    "motif" => $data['motif'],
                    "fees" => $data['fees'],
                    "anamnese" => $data['anamnese'],
                    "examen_physique" => $data['examen_physique'],
                    "diagnostic" => $data['diagnostic'],
                    "antecedent" => $data['antecedent'],
                    "next_appointment" => convertDateTime($data['next_appointment']),
                ]
            );
            return redirect()->route('consultation.index',)->with('success', "Consultation ajouté avec succès");;
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }
    public function update(Request $request, $id)
    {

        $data = $this->validate($request, [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'type_id' => 'required|exists:type_consultations,id',
            'status' => 'required',
            'date' => 'required',
            'motif' => 'required',
            'fees' => 'required',
            'anamnese' => 'nullable',
            'examen_physique' => 'nullable',
            'diagnostic' => 'nullable',
            'antecedent' => 'nullable',
            'payement_mode' => 'nullable',
            'next_appointment' => 'nullable',
        ]);

        $consultation = Consultation::findOrFail($id);

        if (empty($consultation)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }
        // dd($request);

        try {
            $consultation->update(
                [
                    "patient_id" => $data['patient_id'],
                    "doctor_id" => $data['doctor_id'],
                    "type_consultation_id" => $data['type_id'],
                    "status" => $data['status'],
                    "date" => convertDateTime($data['date']),
                    "motif" => $data['motif'],
                    "fees" => $data['fees'],
                    "anamnese" => $data['anamnese'],
                    "examen_physique" => $data['examen_physique'],
                    "diagnostic" => $data['diagnostic'],
                    "antecedent" => $data['antecedent'],
                    "next_appointment" => convertDateTime($data['next_appointment']),
                ]
            );
            return redirect()->route('consultation.index',)->with('success', "Consultation mis à jour avec succès");;
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-patients')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $type = TypeConsultation::find($id)->delete();

        if ($type) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }
}
