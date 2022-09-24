<?php

namespace App\Http\Controllers;

use App\Models\Details_Contrat;
use App\Models\CategoryTest;
use Illuminate\Http\Request;

class DetailsContratController extends Controller
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
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Details_Contrat  $details_Contrat
     * @return \Illuminate\Http\Response
     */
    public function show(Details_Contrat $details_Contrat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Details_Contrat  $details_Contrat
     * @return \Illuminate\Http\Response
     */
    public function edit(Details_Contrat $details_Contrat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Details_Contrat  $details_Contrat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Details_Contrat $details_Contrat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Details_Contrat  $details_Contrat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Details_Contrat $details_Contrat)
    {
        //
    }

    public function getremise($contrat_id, $category_test_id){
        //dd("frh");
        $data = Details_Contrat::where(['contrat_id' => $contrat_id, 'category_test_id' => $category_test_id])->first();
        if($data == null){
            return 0;
        }else{
            return $data->pourcentage;
        }

    }
}
