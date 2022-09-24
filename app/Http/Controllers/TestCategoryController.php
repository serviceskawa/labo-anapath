<?php

namespace App\Http\Controllers;

use App\Models\CategoryTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
        
    public function index(){
        if (getOnlineUser()->can('view-examens-categories')) {
            $testcategories = CategoryTest::with(['tests'])->get();
            // dd($testcategories);
            return view('tests.category.index',compact(['testcategories']));
        }
        return back()->with('error', "Vous n'êtes pas autorisé");
    }

    public function store(Request $request){
        
        if (getOnlineUser()->can('create-examens-categories')) {
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
        return back()->with('error', "Vous n'êtes pas autorisé");
        
    }


    public function update(Request $request){
        if (getOnlineUser()->can('edit-examens-categories')) {
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
        return back()->with('error', "Vous n'êtes pas autorisé");
        
    }


    public function destroy($id){
        if (getOnlineUser()->can('delete-examens-categories')) {
            $categorytest = CategoryTest::find($id)->delete();

            if ($categorytest) {
                return back()->with('success', "    Un élement a été supprimé ! ");
            } else {
                return back()->with('error', "    Element utilisé ailleurs ! ");
            }
        }
        return back()->with('error', "Vous n'êtes pas autorisé");
       
    }


    public function edit($id){
        if (getOnlineUser()->can('edit-examens-categories')) {
            $data = CategoryTest::find($id);
            return response()->json($data);
        }
        return back()->with('error', "Vous n'êtes pas autorisé");
        

    }
}
