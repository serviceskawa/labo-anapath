<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Details_Contrat;
use Illuminate\Http\Request;

class ContratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contrats = Contrat::all();
        return view('contrats.index',compact(['contrats']));
    }

    public function details_index($id){
      
        $contrat = Contrat::find($id);
        $details = Details_Contrat::where('contrat_id',$contrat->id)->get();
        return view('contrats_details.index',compact(['contrat','details']));
 
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
        
        $data=$this->validate($request, [
            'name' => 'required',
            'type' => 'required',   
            'description' => 'required',        
        ]);

        

        try {
             $contrat = new Contrat();
             $contrat->name = $data['name'];
             $contrat->type = $data['type'];
             $contrat->description = $data['description'];
             $contrat->status = 'CREATION';
             $contrat->save();

            return redirect()->route('contrat_details.index',$contrat->id)->with('success', "Contrat enregistré avec succès ! ");
               } catch(\Throwable $ex){
             return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function show(Contrat $contrat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function edit(Contrat $contrat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contrat $contrat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Contrat::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }
}
