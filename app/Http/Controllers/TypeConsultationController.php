<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TypeConsultation;

class TypeConsultationController extends Controller
{
    public function index()
    {
        $types = TypeConsultation::all();

        return view('type_consultation.index', compact('types'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'name' => 'required|unique:type_consultations,name',
            'id' => 'nullable',
        ]);

        try {
            TypeConsultation::updateOrCreate(
                [
                    "id" => $data['id']
                ],
                [
                    "name" => $data['name'],
                    "slug" => Str::slug($data['name']),
                ]
            );
            return back()->with('success', "Type de consultation ajouté avec succès");
        } catch (\Throwable $ex) {
            $error = $ex->getMessage();
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    public function show($id)
    {
        $type = TypeConsultation::find($id);

        if (empty($type)) {
            return response()->json(['error' => "Ce Type de consultation n'hexiste pas"]);
        }

        return response()->json($type);
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
