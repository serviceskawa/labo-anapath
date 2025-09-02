<?php

namespace App\Http\Controllers;

use App\Http\Requests\PrestationRequest;
use App\Models\CategoryPrestation;
use App\Models\Prestation;
use App\Models\Setting;
use Illuminate\Http\Request;

class PrestationController extends Controller
{
    protected $prestations;
    protected $categories;
    protected $setting;

    public function __construct(Prestation $prestations, CategoryPrestation $categories, Setting $setting){
        $this->prestations = $prestations;
        $this->categories = $categories;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $prestations = $this->prestations->all();

        $categories = $this->categories->all();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('prestation.index', compact(['prestations', 'categories']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrestationRequest $request)
    {

        $data = [
            'price' => $request->price,
            'name' => $request->name,
            'category_prestation_id' => $request->category_prestation_id,
        ];

        try {
            $this->prestations->create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->prestations->find($id);
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
        // if (!getOnlineUser()->can('edit-tests')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = [
            'id' => $request->id,
            'price' => $request->price,
            'name' => $request->name,
            'category_prestation_id' => $request->category_prestation_id
        ];

        try {
            $prestation = $this->prestations->find($data['id']);
            $prestation->name = $data['name'];
            $prestation->price = $data['price'];
            $prestation->category_prestation_id = $data['category_prestation_id'];
            $prestation->save();
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prestation  $prestation
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-tests')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $prestation = $this->prestations->find($id)->delete();

        if ($prestation) {
            return back()->with('success', " Elément supprimé avec succès  ! ");
        } else {
            return back()->with('error', "Cette prestation est utilisée ! ");
        }
    }
    public function show_by_id($id)
    {
        // if (!getOnlineUser()->can('delete-tests')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = $this->prestations->find($id);
        return response()->json($data);
    }
}
