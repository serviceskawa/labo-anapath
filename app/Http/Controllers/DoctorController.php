<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (getOnlineUser()->can('view-medecins-traitants')) {
            $doctors = Doctor::orderBy('name','asc')->get();

            return view('doctors.index',compact(['doctors']));
        }

        return back()->with('error', "Vous n'êtes pas autorisé");

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
        if (getOnlineUser()->can('store-medecins-traitants')) {
            $data = $this->validate($request, [
                'name' => 'required',
                'email' => 'nullable',
                'role' => 'nullable',
                'telephone' => 'required',
                'commission' => 'required|numeric|min:0|max:100',
            ]);
    
    
    
            try {
                Doctor::create($data);
                return back()->with('success', "Un médecin enregistré ! ");
    
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
            }
        }
        return back()->with('error', "Vous n'êtes pas autorisé");        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Doctor::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (getOnlineUser()->can('edit-medecins-traitants')) {
            $data=$this->validate($request, [
                'id2' => 'required',
                'name' => 'required',
                'email' => 'nullable',
                'role' => 'required',
                'telephone' => 'required',
                'commission' => 'required',
            ]);
    
    
    
            try {
    
                $doctor = Doctor::find($data['id2']);
                $doctor->name = $data['name'];
                $doctor->email = $data['email'];
                $doctor->role = $data['role'];
                $doctor->telephone = $data['telephone'];
                $doctor->commission = $data['commission'];
                $doctor->save();
    
                return back()->with('success', "Un médecin a été mis à jour ! ");
    
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
            }
        }
        return back()->with('error', "Vous n'êtes pas autorisé");

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (getOnlineUser()->can('delete-medecins-traitants')) {
            Doctor::find($id)->delete();
            return back()->with('success', "    Un élement a été supprimé ! ");
        }
        return back()->with('error', "Vous n'êtes pas autorisé");
    }
}
