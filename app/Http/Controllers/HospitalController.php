<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-hopitaux')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $hopitals = Hospital::orderBy('name','asc')->get();
        return view('hopitals.index',compact(['hopitals']));

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
        if (!getOnlineUser()->can('create-hopitaux')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data=$this->validate($request, [
            'name' => 'required',
            'adresse' => 'nullable',    
            'email' => 'nullable',        
            'telephone' => 'nullable',  
            'commission' => 'nullable|numeric|min:0|max:100',  
        ]);

        

        try {
            Hospital::create($data);
            return back()->with('success', "Un hôpital a été enregistré ! ");

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
        if (!getOnlineUser()->can('edit-hopitaux')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = Hospital::find($id);
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
        if (!getOnlineUser()->can('edit-hopitaux')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data=$this->validate($request, [
            'name2' => 'required',
            'id2' => 'required',
            'adresse2' => 'nullable',    
            'email2' => 'nullable',        
            'telephone2' => 'required',  
            'commission2' => 'required |numeric',  
        ]);

        
        try {
            $hopital = Hospital::find($data['id2']);
            $hopital->name = $data['name2'];
            $hopital->adresse = $data['adresse2'];
            $hopital->email = $data['email2'];
            $hopital->telephone = $data['telephone2'];
            $hopital->commission = $data['commission2'];
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
        if (!getOnlineUser()->can('delete-hopitaux')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        Hospital::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");

    }
}
