<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TypeConsultation;
use App\Models\TypeConsultationFile;

class TypeConsultationController extends Controller
{
    protected $setting;
    protected $typeConsultation;
    protected $typeConsultationFile;
    
    public function __construct(Setting $setting, TypeConsultation $typeConsultation, TypeConsultationFile $typeConsultationFile){
        $this->setting = $setting;
        $this->typeConsultation = $typeConsultation;
        $this->typeConsultationFile = $typeConsultationFile;
    }
    public function index()
    {
        $types = $this->typeConsultation->all();

        $files = $this->typeConsultationFile->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('type_consultation.index', compact('types', 'files'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required',
            'type_files' => 'required',
            'id' => 'nullable',
        ]);

        try {
            $type = $this->typeConsultation->updateOrCreate(
                [
                    "id" => $data['id']
                ],
                [
                    "name" => $data['name'],
                    "slug" => Str::slug($data['name']),
                ]
            );

            $filesIds = array_keys($request->type_files);
            $type->type_files()->sync($filesIds);

            return redirect()->route('type_consultation.index')->with('success', "Type de consultation ajouté avec succès");
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            // dd($error);
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function show($id)
    {
        $type = $this->typeConsultation->find($id);

        if (empty($type)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }
        $files = $this->typeConsultationFile->all();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('type_consultation.create', compact('type', 'files'));
    }

    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-patients')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $type = $this->typeConsultation->find($id)->delete();

        if ($type) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }
}
