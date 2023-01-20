<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TypeConsultation;
use App\Models\TypeConsultationFile;

class TypeConsultationController extends Controller
{
    public function index()
    {
        $types = TypeConsultation::all();

        $files = TypeConsultationFile::all();
        return view('type_consultation.index', compact('types', 'files'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $data = $this->validate($request, [
            'name' => 'required',
            'type_files' => 'required',
            'id' => 'nullable',
        ]);
        // dd(array_keys($request->type_files));

        try {
            $type = TypeConsultation::updateOrCreate(
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

            return back()->with('success', "Type de consultation ajouté avec succès");
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            // dd($error);
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function show($id)
    {
        $type = TypeConsultation::find($id);

        if (empty($type)) {
            return back()->with('error', "Une Erreur est survenue. Cette consultation n'existe pas");
        }
        $files = TypeConsultationFile::all();
        return view('type_consultation.create', compact('type', 'files'));
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
