<?php

namespace App\Http\Controllers;

use App\Models\UnitMeasurement;
use App\Models\Setting;
use Illuminate\Http\Request;

class UnitMeasurementController extends Controller
{
    protected $unitMeasurement;
    protected $setting;

    public function __construct(UnitMeasurement $unitMeasurement, Setting $setting){
        $this->unitMeasurement = $unitMeasurement;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-unit_measurements')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $unitMeasurements = $this->unitMeasurement->latest()->get();

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);

        return view('unites_measurement.index',compact(['unitMeasurements']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-unit_measurements')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $data = [
            'name' => $request->name,
        ];

        try {
            $this->unitMeasurement->create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UnitMeasurement  $unitMeasurement
     * @return \Illuminate\Http\Response
     */
    public function show(UnitMeasurement $unitMeasurement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UnitMeasurement  $unitMeasurement
     * @return \Illuminate\Http\Response
     */
    public function edit(UnitMeasurement $unitMeasurement)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UnitMeasurement  $unitMeasurement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UnitMeasurement $unitMeasurement)
    {
        // if (!getOnlineUser()->can('edit-unit_measurements')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        try {
                $unitMeasurement->update([
                    'name' => $request->name
                ]);

                return back()->with('success', " Mise à jour effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
            }
    }

    /**
     * Remove the specified resource from storge.
     *
     * @param  \App\Models\UnitMeasurement  $unitMeasurement
     * @return \Illuminate\Http\Response
     */
    public function delete($unitMeasurement)
    {
        // if (!getOnlineUser()->can('delete-unit_measurements')) {
        //   return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        // $unitMeasurement->delete();
        $this->unitMeasurement->find($unitMeasurement)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
