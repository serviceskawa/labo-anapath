<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestCategoryRequest;
use App\Models\CategoryTest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestCategoryController extends Controller
{
    protected $categoryTest;
    protected $setting;
    public function __construct(CategoryTest $categoryTest, Setting $setting)
    {
        $this->middleware('auth');
        $this->categoryTest = $categoryTest;
        $this->setting = $setting;
    }

    public function index(){
        if (!getOnlineUser()->can('view-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $testcategories = $this->categoryTest->with(['tests'])->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        // dd($testcategories);
        return view('tests.category.index',compact(['testcategories']));
    }

    public function store(TestCategoryRequest $request){
        if (!getOnlineUser()->can('create-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data= [
            'code' => $request->code,
            'name' => $request->name,
        ];

        try {
            $category = $this->categoryTest->create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }


    public function update(TestCategoryRequest $request){
        if (!getOnlineUser()->can('edit-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data= [
            'id' => $request->id,
            'code' => $request->code,
            'name' => $request->name,
        ];

        try {
           $categorie = $this->categoryTest->find($data['id']);
           $categorie->code = $data['code'];
           $categorie->name = $data['name'];
           $categorie->save();

            return back()->with('success', " Mise à jour effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }


    public function destroy($id){
        if (!getOnlineUser()->can('delete-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $categorytest = $this->categoryTest->find($id)->delete();

        if ($categorytest) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }


    public function edit($id){
        if (!getOnlineUser()->can('edit-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->categoryTest->find($id);
        return response()->json($data);
    }
}
