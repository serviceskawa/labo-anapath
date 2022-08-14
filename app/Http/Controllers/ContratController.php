<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\CategoryTest;
use Illuminate\Http\Request;
use App\Models\Details_Contrat;
use Illuminate\Support\Facades\DB;

class ContratController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contrats = Contrat::with(['orders','detail'])->get();
        // dd($contrats);
        return view('contrats.index',compact(['contrats']));
    }

    public function details_index($id){

        $contrat = Contrat::find($id);
        $test_caterories = CategoryTest::all();
        $details = Details_Contrat::where('contrat_id',$contrat->id)->get(); 
        return view('contrats_details.index',compact(['contrat','details','test_caterories']));

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
             $contrat->status = 'INACTIF';
             $contrat->save();

            return redirect()->route('contrat_details.index',$contrat->id)->with('success', "Contrat enregistré avec succès ! ");
               } catch(\Throwable $ex){
             return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
         }
    }


    public function details_store(Request $request){

        $data = $this->validate($request, [
            'contrat_id' => 'required',
            'pourcentage' => 'required',
            'category_test_id' => 'required',
        ]);

        $contrat = Contrat::findorfail($data['contrat_id']);
        $category_exit = $contrat->detail()->whereCategoryTestId($data['category_test_id'])->exists();

        if ($category_exit) {
            return back()->with('error', "Cette categorie existe ! ");
        }

        try {

            DB::transaction(function () use ($data) {
                Details_Contrat::create($data);
            });

            return back()->with('success', "Element enregistré avec succès ! ");

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
    public function edit($id)
    {
        $data = Contrat::find($id);
        return response()->json($data);
    }


    public function contrat_details_edit($id)
    {
        $data = Details_Contrat::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $data = $this->validate($request, [
            'name2' => 'required',
            'type2' => 'required',
            'status2' => 'required',
            'id2' => 'required',
            'description2' => 'required',
        ]);

        try {
            DB::transaction(function () use ($data) {

                $contrat = Contrat::find($data['id2']);
                $contrat->name = $data['name2'];
                $contrat->type = $data['type2'];
                $contrat->status = $data['status2'];
                $contrat->description = $data['description2'];
                $contrat->save();
            });

             return back()->with('success', "Mise à jour effectuée avec succès ! ");
            } catch(\Throwable $ex){
          return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }



    }


    public function contrat_details_update(Request $request){


        $data=$this->validate($request, [
            'category_test_id2' => 'required',
            'pourcentage2' => 'required',
            'contrat_id2' => 'required',
            'contrat_details_id2' => 'required',
        ]);



        try {
            DB::transaction(function () use ($data) {
                $contrat_detail = Details_Contrat::find($data['contrat_details_id2']);
                $contrat_detail->category_test_id = $data['category_test_id2'];
                $contrat_detail->pourcentage = $data['pourcentage2'];
                $contrat_detail->save();
            });

             return back()->with('success', "Mise à jour effectué avec succès ! ");
            } catch(\Throwable $ex){
          return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contrat  $contrat
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contrat = Contrat::find($id)->delete();
        if ($contrat) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Ce contrat est utilisé ailleurs ! ");
        }
        
    }


    public function destroy_details($id)
    {
        Details_Contrat::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function update_detail_status($id)
    {
        $contrat = Contrat::findorfail($id);
        $contrat->fill(["status" => "ACTIF"])->save();

        return redirect()->route('contrats.index')->with('success', "Statut mis à jour ! ");
    }

}
