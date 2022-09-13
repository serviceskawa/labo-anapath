<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::orderBy('name','asc')->get();

        return view('doctors.index',compact(['doctors']));

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Doctor::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }
}
