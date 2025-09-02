<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Prestation;
use App\Models\Consultation;
use Illuminate\Http\Request;
use App\Models\TypeConsultation;
use App\Models\CategoryPrestation;
use Illuminate\Support\Facades\Auth;
use App\Models\ConsultationTypeConsultationFiles;
use App\Models\Setting;

class ConsultationController extends Controller
{

    protected $setting;
    protected $consultation;
    public function __construct(Setting $setting, Consultation $consultation){
        $this->setting = $setting;
        $this->consultation = $consultation;
    }
    public function index()
    {
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('consultation.index');
    }

    public function getConsultations()
    {
        $data = $this->consultation->with(['doctor', 'patient', 'type', 'attribuateToDoctor'])->latest()->get();

        return Datatables::of($data)->addIndexColumn()
            ->editColumn('created_at', function ($data) {
                //change over here
                return $data->date;
            })
            ->addColumn('action', function ($data) {
                $btnVoir = '<a type="button" href="' . route('consultation.show', $data->id) . '" class="btn btn-primary" title="Voir les détails"><i class="mdi mdi-eye"></i></a>';
                $btnEdit = ' <a type="button" href="' . route('consultation.edit', $data->id) . '" class="btn btn-warning" title="Mettre à jour examen"><i class="mdi mdi-doctor"></i></a>';
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

                return $btnVoir . $btnEdit;
                // return $btnVoir . $btnEdit . $btnReport . $btnInvoice . $btnDelete;
            })
            ->addColumn('patient', function ($data) {
                return $data->patient->firstname . ' ' . $data->patient->lastname;
            })
            ->addColumn('doctor', function ($data) {
                if (empty($data->attribuateToDoctor)) {
                    $result = "";
                } else {
                    $result = $data->attribuateToDoctor->firstname;
                }
                return $result;
            })
            ->addColumn('type', function ($data) {
                if (empty($data->type)) {
                    return "";
                } else {
                    return $data->type->name;
                }
            })
            ->rawColumns(['action', 'patient', 'doctor', 'type'])
            ->make(true);
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $types = TypeConsultation::all();

        $categoryPrestation =  CategoryPrestation::whereSlug('Consultation')->first();
        $prestations = $categoryPrestation->prestations;

        $user = Auth::user();

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('consultation.create', compact('patients', 'doctors', 'types', 'prestations', 'user'));
    }

    public function show($id)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();
        $types = TypeConsultation::all();

        $consultation = Consultation::findOrFail($id);

        $categoryPrestation =  CategoryPrestation::whereSlug('Consultation')->first();
        $prestations = $categoryPrestation->prestations;
        if (empty($consultation)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('consultation.show', compact('patients', 'doctors', 'types', 'consultation', 'prestations'));
    }

    // Affiche la vue docteur pour edition
    public function edit($id)
    {
        if (!getOnlineUser()->hasRole('doctors')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $patients = Patient::all();
        $doctors = Doctor::all();
        $types = TypeConsultation::all();

        $consultation = Consultation::findOrFail($id);

        $categoryPrestation =  CategoryPrestation::whereSlug('Consultation')->first();
        $prestations = $categoryPrestation->prestations;
        if (empty($consultation)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('consultation.edit_from_receptionnist', compact('patients', 'doctors', 'types', 'consultation', 'prestations'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'patient_id' => 'required|exists:patients,id',
            // 'doctor_id' => 'required|exists:doctors,id',
            'type_id' => 'nullable',
            'status' => 'nullable',
            'date' => 'required',
            'motif' => 'nullable',
            'fees' => 'nullable',
            'anamnese' => 'nullable',
            'examen_physique' => 'nullable',
            'diagnostic' => 'nullable',
            'antecedent' => 'nullable',
            'payement_mode' => 'nullable',
            'next_appointment' => 'nullable',
            'id' => 'nullable',
            'prestation_id' => 'required|exists:prestations,id',
            'doctor_id' => 'nullable',
        ]);

        $latest = Consultation::orderBy('id', 'DESC')->first();
        $code = sprintf('%04d', empty($latest->id) ? "1" : $latest->id);

        $prestation = Prestation::findorfail($data['prestation_id']);

        if (empty($prestation)) {
            return redirect()->route('consultation.index',)->with('error', "Cette prestation n'existe pas");;
        }

        try {
            Consultation::Create(
                [
                    "code" => "CON" . $code,
                    "patient_id" => $data['patient_id'],
                    // "doctor_id" => $data['doctor_id'],
                    "type_consultation_id" => empty($data['type_id']) ? null : $data['type_id'],
                    "status" => empty($data['status']) ? "pending" : $data['status'],
                    "date" => convertDateTime($data['date']),
                    "fees" => $prestation->price,
                    "payement_mode" => !empty($data['payement_mode']) ? $data['payement_mode'] : "espèce",
                    "next_appointment" => !empty($data['next_appointment']) ? convertDateTime($data['next_appointment']) : null,
                    "prestation_id" => $data['prestation_id'],
                    "attribuate_doctor_id" => $data['doctor_id'],
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
            // 'doctor_id' => 'required|exists:doctors,id',
            'type_id' => 'nullable',
            'status' => 'required',
            'date' => 'required',
            'motif' => 'nullable',
            'fees' => 'nullable',
            'anamnese' => 'nullable',
            'examen_physique' => 'nullable',
            'diagnostic' => 'nullable',
            'antecedent' => 'nullable',
            'payement_mode' => 'nullable',
            'next_appointment' => 'nullable',
            'prestation_id' => 'required|exists:prestations,id',
            'doctor_id' => 'nullable',
        ]);

        $consultation = Consultation::findOrFail($id);

        if (empty($consultation)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }

        $prestation = Prestation::findorfail($data['prestation_id']);
        if (empty($prestation)) {
            return redirect()->route('consultation.index',)->with('error', "Cette prestation n'existe pas");;
        }

        $tab = [];
        $tabFile = [];
        if ($request->hasfile('type_file')) {
            foreach ($request->file('type_file') as $key => $value) {

                $img = time() . 'consultation.' . $value->extension();

                $path_img = $value->storeAs('consultations/' . $consultation->patient->code . '/' . $consultation->type->name, $img, 'public');
                $tabFile[$key] = $path_img;
            }
        }

        if (!empty($consultation->type)) {
            foreach ($consultation->type->type_files as $type_file) {
                $tab[$type_file->id] = [
                    "consultation_id" => $consultation->id,
                    "type_id" => $consultation->type_consultation_id,
                    "type_file_id" => $type_file->id,
                    "path" => empty($tabFile[$type_file->id]) ? "" : $tabFile[$type_file->id],
                    "comment" => empty($request->comment[$type_file->id]) ? "" : $request->comment[$type_file->id],
                ];
            }
        }
        try {
            $consultation->update(
                [
                    "patient_id" => $data['patient_id'],
                    // "doctor_id" => $data['doctor_id'],
                    // "type_consultation_id" => $data['type_id'],
                    "status" => $data['status'],
                    "date" => convertDateTime($data['date']),
                    // "motif" => $data['motif'],
                    "fees" => $prestation->price,
                    // "anamnese" => $data['anamnese'],
                    // "examen_physique" => $data['examen_physique'],
                    // "diagnostic" => $data['diagnostic'],
                    // "antecedent" => $data['antecedent'],
                    // "next_appointment" => convertDateTime($data['next_appointment']),
                    "prestation_id" => $data['prestation_id'],
                    "payement_mode" => !empty($data['payement_mode']) ? $data['payement_mode'] : "espèce",
                    "next_appointment" => !empty($data['next_appointment']) ? convertDateTime($data['next_appointment']) : null,
                    "attribuate_doctor_id" => $data['doctor_id'],
                ]
            );

            if (!empty($consultation->type)) {
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
                }
            }

            // ConsultationTypeConsultationFiles::insert($tab);
            return redirect()->route('consultation.index',)->with('success', "Consultation mis à jour avec succès");;
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function update_by_doctor(Request $request, $id)
    {
        $data = $this->validate($request, [
            'motif' => 'nullable',
            'anamnese' => 'nullable',
            'examen_physique' => 'nullable',
            'diagnostic' => 'nullable',
            'antecedent' => 'nullable',
        ]);

        $consultation = Consultation::findOrFail($id);

        if (empty($consultation)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }

        $tab = [];
        $tabFile = [];

        if ($request->hasfile('type_file')) {
            foreach ($request->file('type_file') as $key => $value) {

                $img = time() . 'consultation.' . $value->extension();

                $path_img = $value->storeAs('consultations/' . $consultation->patient->code . '/' . $consultation->type->name, $img, 'public');
                $tabFile[$key] = $path_img;
            }
        }

        if (!empty($consultation->type)) {
            foreach ($consultation->type->type_files as $type_file) {
                $tab[$type_file->id] = [
                    "consultation_id" => $consultation->id,
                    "type_id" => $consultation->type_consultation_id,
                    "type_file_id" => $type_file->id,
                    "path" => empty($tabFile[$type_file->id]) ? "" : $tabFile[$type_file->id],
                    "comment" => empty($request->comment[$type_file->id]) ? "" : $request->comment[$type_file->id],
                ];
            }
        }
        try {
            $consultation->update(
                [
                    "motif" => $data['motif'],
                    "anamnese" => $data['anamnese'],
                    "examen_physique" => $data['examen_physique'],
                    "diagnostic" => $data['diagnostic'],
                    "antecedent" => $data['antecedent'],
                ]
            );

            if (!empty($consultation->type)) {
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
                }
            }

            // ConsultationTypeConsultationFiles::insert($tab);
            return redirect()->route('consultation.index',)->with('success', "Consultation mis à jour avec succès");;
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function update_type_consultation(Request $request)
    {
        $data = $this->validate($request, [
            'type_consultation_id' => 'required',
            'consultation_id' => 'required',
        ]);
        $consultation = Consultation::findOrFail($data['consultation_id']);

        try {
            $consultation->update(
                [
                    "type_consultation_id" => $data['type_consultation_id'],
                ]
            );
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
