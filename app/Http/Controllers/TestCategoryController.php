<?php

namespace App\Http\Controllers;

use App\Models\CategoryTest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
        
    public function index(){
        if (!getOnlineUser()->can('view-examens-categories')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $testcategories = CategoryTest::with(['tests'])->orderBy('created_at', 'DESC')->get();
        $setting = Setting::find(1);
        config(['app.name' => $setting->titre]);
        // dd($testcategories);
        return view('tests.category.index',compact(['testcategories']));
    }

    public function store(Request $request){
        if (!getOnlineUser()->can('create-examens-categories')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
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
        if (!getOnlineUser()->can('edit-examens-categories')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
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
        if (!getOnlineUser()->can('delete-examens-categories')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $categorytest = CategoryTest::find($id)->delete();

        if ($categorytest) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }


    public function edit($id){
        if (!getOnlineUser()->can('edit-examens-categories')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = CategoryTest::find($id);
        return response()->json($data);
    }
}
