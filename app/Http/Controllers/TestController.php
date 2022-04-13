<?php

namespace App\Http\Controllers;

use App\Models\CategoryTest;
use App\Models\Test;
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
        $tests = Test::with('category_test')->get();
   
        $categories = CategoryTest::all();
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
      
        $data=$this->validate($request, [
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
       $test = Test::find($id);
       $test->delete();
        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
