<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Models\Doctor;
use App\Models\Setting;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    protected $doctor;
    protected $setting;
    public function __construct( Doctor $doctor, Setting $setting)
    {
        $this->middleware('auth'); 
        $this->doctor = $doctor;
        $this->setting = $setting;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-doctors')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        //récupérer les données de la table doctor dans l'ordre croissant des noms
        $doctors = $this->doctor->oldest('name')->get();

        $setting = $this->setting::find(1);
        config(['app.name' => $setting->titre]);
        return view('doctors.index',compact(['doctors']));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorRequest $request)
    {
        if (!getOnlineUser()->can('create-doctors')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        
        // Récupérer les données saisir par l'utilisateur et qui respectent les conditions
        $doctorData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'telephone' => $request->telephone,
            'commission' => $request->commission,
        ];


        // insérer les données dans la base de données
        try {
            Doctor::create($doctorData);
            return back()->with('success', "Un médecin enregistré ! ");

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
        if (!getOnlineUser()->can('edit-doctors')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $doctorData = $this->doctor->find($id);
        return response()->json($doctorData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DoctorRequest $request)
    {
        if (!getOnlineUser()->can('edit-doctors')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $doctorData=[
            'id2' => $request->id2,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'telephone' => $request->telephone,
            'commission' => $request->commission,
        ];



        try {

            $doctor = Doctor::find($doctorData['id2']);
            $doctor->name = $doctorData['name'];
            $doctor->email = $doctorData['email'];
            // $doctor->role = $doctorData['role'];
            $doctor->telephone = $doctorData['telephone'];
            $doctor->commission = $doctorData['commission'];
            $doctor->save();

            return back()->with('success', "Un médecin a été mis à jour ! ");

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
        if (!getOnlineUser()->can('delete-doctors')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->doctor->find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function storeDoctor(DoctorRequest $request)
    {
        $data = [
            'name' => $request->name,
        ];

        $exist = $this->doctor->where('id',$request->name)->first();
        try {
            if ($exist === null ) {
                $doctor = $this->doctor->create($data);
                $status = "created";

            }else {
                $doctor = [];
                $status = "exist";
            }

            return response()->json(["doctor" =>$doctor, "status"=>$status], 200);

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }        
    }
}
