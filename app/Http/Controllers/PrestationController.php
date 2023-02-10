<?php

namespace App\Http\Controllers;

use App\Models\CategoryPrestation;
use App\Models\Prestation;
use Illuminate\Http\Request;

class PrestationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $prestations = Prestation::all();

        $categories = CategoryPrestation::all();

        return view('prestation.index', compact(['prestations', 'categories']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-examens')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        // dd($request);
        $data = $this->validate($request, [
            'price' => 'required |numeric|gt:0',
            'name' => 'required |unique:prestations,name',
            'category_prestation_id' => 'required'
        ]);

        try {
            Prestation::create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prestation  $Prestation
     * @return \Illuminate\Http\Response
     */
    public function show(Prestation $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = Prestation::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('edit-examens')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = $this->validate($request, [
            'id2' => 'required ',
            'price2' => 'required |numeric|gt:0',
            'name2' => 'required ',
            'category_prestation_id2' => 'required'
        ]);

        try {
            $prestation = Prestation::find($data['id2']);
            $prestation->name = $data['name2'];
            $prestation->price = $data['price2'];
            $prestation->category_prestation_id = $data['category_prestation_id2'];
            $prestation->save();
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-examens')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $prestation = Prestation::find($id)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
