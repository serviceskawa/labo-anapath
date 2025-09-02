<?php

namespace App\Http\Controllers;

use App\Http\Requests\HospitalRequest;
use App\Models\Hospital;
use App\Models\Setting;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    protected $setting;
    protected $hopitals;

    public function __construct(Setting $setting, Hospital $hopitals,){
        $this->hopitals = $hopitals;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-hospitals')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $hopitals = $this->hopitals->latest()->get();

        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('hopitals.index',compact(['hopitals']));

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HospitalRequest $request)
    {
        if (!getOnlineUser()->can('create-hospitals')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $hospitalData=[
            'name' => $request->name,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'commission' => $request->commission,
        ];



        try {
            $this->hopitals->create($hospitalData);
            return back()->with('success', "Un hôpital a été enregistré ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    public function storeHospital(HospitalRequest $request)
    {
        if (!getOnlineUser()->can('create-hospitals')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $hospitalData = [
            'name' => $request->name,
        ];

        $exist = $this->hopitals->where('id',$request->name)->first();

        try {
            if ($exist === null ) {
                $hospital = $this->hopitals->create($hospitalData);
                $status = "created";

            }else {
                $hospital = [];
                $status = "exist";
            }

            return response()->json(["hospital" =>$hospital, "status"=>$status], 200);

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-hospitals')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $hospitalData = $this->hopitals->find($id);
        return response()->json($hospitalData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HospitalRequest $request)
    {
        if (!getOnlineUser()->can('edit-hospitals')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $hospitalData=[
            'id' => $request->id,
            'name' => $request->name,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'commission' => $request->commission,
        ];


        try {
            $hopital = $this->hopitals->find($hospitalData['id']);
            $hopital->name = $hospitalData['name'];
            $hopital->adresse = $hospitalData['adresse'];
            $hopital->email = $hospitalData['email'];
            $hopital->telephone = $hospitalData['telephone'];
            $hopital->commission = $hospitalData['commission'];
            $hopital->save();

            return back()->with('success', "Un hôpital a été mis à jour ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
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
        if (!getOnlineUser()->can('delete-hospitals')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->hopitals->find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");

    }
}
