<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Models\TypeConsultation;
use App\Models\ConsultationTypeConsultationFiles;

class ConsultationController extends Controller
{

    public function index()
    {
        return view('consultation.index');
    }

    public function getConsultations()
    {
        $data = Consultation::with(['doctor', 'patient', 'type'])->orderBy('created_at', 'desc')->get();

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                //change over here
                return $data->date;
            })
            ->addColumn('action', function ($data) {
                $btnVoir = '<a type="button" href="' . route('consultation.show', $data->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>';
                $btnEdit = ' <a type="button" href="' . route('consultation.show', $data->id) . '" class="btn btn-primary" title="Mettre à jour examen"><i class="mdi mdi-lead-pencil"></i></a>';
                if ($data->status != 1) {
                    $btnReport = ' <a type="button" href="' . route('details_test_order.index', $data->id) . '" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>';
                    $btnDelete = ' <button type="button" onclick="deleteModal(' . $data->id . ')" class="btn btn-danger" title="Supprimer"><i class="mdi mdi-trash-can-outline"></i> </button>';
                } else {
                    $btnReport = ' <a type="button" href="' . route('report.show', $data->report->id) . '" class="btn btn-warning" title="Compte rendu"><i class="uil-file-medical"></i> </a>';
                    $btnDelete = "";
                }

                if (!empty($data->invoice->id)) {
                    $btnInvoice = ' <a type="button" href="' . route('invoice.show', $data->invoice->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';
                } else {
                    $btnInvoice = ' <a type="button" href="' . route('invoice.storeFromOrder', $data->id) . '" class="btn btn-success" title="Facture"><i class="mdi mdi-printer"></i> </a>';
                }

                return $btnVoir;
                // return $btnVoir . $btnEdit . $btnReport . $btnInvoice . $btnDelete;
            })
            ->addColumn('patient', function ($data) {
                return $data->patient->firstname . ' ' . $data->patient->lastname;
            })
            ->addColumn('doctor', function ($data) {
                return $data->doctor->name;
            })
            ->addColumn('type', function ($data) {
                return $data->type->name;
            })
            ->rawColumns(['action', 'patient', 'doctor', 'type'])
            ->make(true);
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
        // dd(getConsultationTypeFiles(2, 2)->path);
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
            'motif' => 'nullable',
            'fees' => 'required',
            'anamnese' => 'nullable',
            'examen_physique' => 'nullable',
            'diagnostic' => 'nullable',
            'antecedent' => 'nullable',
            'payement_mode' => 'required',
            'next_appointment' => 'nullable',
            'id' => 'nullable',
        ]);

        // dd($request);
        $latest = Consultation::orderBy('id', 'DESC')->first();
        $code = sprintf('%04d', empty($latest->id) ? "1" : $latest->id);

        try {
            Consultation::Create(
                [
                    "code" => "CON" . $code,
                    "patient_id" => $data['patient_id'],
                    "doctor_id" => $data['doctor_id'],
                    "type_consultation_id" => $data['type_id'],
                    "status" => $data['status'],
                    "date" => convertDateTime($data['date']),
                    "fees" => $data['fees'],
                    "payement_mode" => $data['payement_mode'],
                    "next_appointment" => convertDateTime($data['next_appointment']),
                ]
            );
            return redirect()->route('consultation.index',)->with('success', "Consultation ajouté avec succès");;
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            // dd($error);
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $data = $this->validate($request, [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'type_id' => 'required|exists:type_consultations,id',
            'status' => 'required',
            'date' => 'required',
            'motif' => 'nullable',
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
        $tab = [];
        $tabFile = [];

        if ($request->hasfile('type_file')) {
            foreach ($request->file('type_file') as $key => $value) {

                $img = time() . 'consultation.' . $value->extension();

                $path_img = $value->storeAs('consultations/' . $consultation->patient->code . '/' . $consultation->type->name, $img, 'public');
                $tabFile[$key] = $path_img;
            }
        }

        // dd('a');
        foreach ($consultation->type->type_files as $type_file) {
            $tab[$type_file->id] = [
                "consultation_id" => $consultation->id,
                "type_id" => $consultation->type_consultation_id,
                "type_file_id" => $type_file->id,
                "path" => empty($tabFile[$type_file->id]) ? "" : $tabFile[$type_file->id],
                "comment" => empty($request->comment[$type_file->id]) ? "" : $request->comment[$type_file->id],
            ];
        }
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

            foreach ($tab as $key => $value) {
                $exist = ConsultationTypeConsultationFiles::whereConsultationId($consultation->id)
                    ->where('type_id', $consultation->type_consultation_id)
                    ->where('type_file_id', $key)->first();
                ConsultationTypeConsultationFiles::updateOrInsert([
                    "consultation_id" => $value['consultation_id'],
                    "type_id" => $value['type_id'],
                    "type_file_id" => $value['type_file_id'],
                ], [
                    "path" => empty($value['path']) ? (empty($exist) ? "" : $exist->path) : $value['path'],
                    "comment" => empty($value['comment']) ? (empty($exist) ? "" : $exist->comment) : $value['comment'],
                ]);
                // dd($exist);
            }

            // ConsultationTypeConsultationFiles::insert($tab);
            return redirect()->route('consultation.index',)->with('success', "Consultation mis à jour avec succès");;
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            dd($error);
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
