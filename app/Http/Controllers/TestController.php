<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Contrat;
use App\Models\CategoryTest;
use App\Models\Details_Contrat;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $tests = Test::orderBy('created_at','DESC')->get();
   
        $categories = CategoryTest::orderBy('created_at','DESC')->get();

        return view('tests.index',compact(['tests','categories']));

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
        if (!getOnlineUser()->can('create-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'price' => 'required |numeric|gt:0',
            'name' => 'required |unique:tests,name',  
            'category_test_id'=>'required'        
        ]);

        try {
            Test::create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = Test::find($id);
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
        if (!getOnlineUser()->can('edit-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data=$this->validate($request, [
            'id2' => 'required ', 
            'price2' => 'required |numeric|gt:0',
            'name2' => 'required ',  
            'category_test_id2'=>'required'        
        ]);

        try {
            $test = Test::find($data['id2']);
            $test->name = $data['name2'];
            $test->price = $data['price2'];
            $test->category_test_id = $data['category_test_id2'];
            $test->save();
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-examens')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test = Test::find($id)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }

    public function getTestAndRemise(Request $request)
    {

        $data = Test::find($request->testId);

        $detail = Details_Contrat::where(['contrat_id' => $request->contratId, 'category_test_id' => $request->categoryTestId])->first();
        if($detail == null){
            $detail = 0;
        }else{
            $detail = $detail->pourcentage;
        }
        return response()->json(["data"=>$data,"detail"=>$detail]);
    }
}
