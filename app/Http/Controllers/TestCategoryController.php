<?php

namespace App\Http\Controllers;

use App\Models\CategoryTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestCategoryController extends Controller
{
    
    public function index(){
        $testcategories = CategoryTest::all();
        return view('tests.category.index',compact(['testcategories']));
    }

    public function store(Request $request){
        
        $data=$this->validate($request, [
            'code' => 'required',
            'name' => 'required |unique:category_tests,name',          
        ]);

        try {
            CategoryTest::create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }


    public function update(Request $request){

        $data=$this->validate($request, [
            'id2' => 'required',
            'code2' => 'required',
            'name2' => 'required',          
        ]);

        try {
           $categorie = CategoryTest::find($data['id2']);
           $categorie->code = $data['code2'];
           $categorie->name = $data['name2'];
           $categorie->save();
           
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }


    public function destroy($id){
        $categorytest = CategoryTest::find($id);
        $categorytest->delete();
        return back()->with('success', " Elément supprimé avec succès  ! ");
    }


    public function edit($id){
        $data = CategoryTest::find($id);
        return response()->json($data);

    }
}
