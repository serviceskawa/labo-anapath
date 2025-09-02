<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\CategoryPrestation;
use App\Models\Setting;

class CategoryPrestationController extends Controller
{

    protected $categoryPrestation;
    protected $setting;

    public function __construct(CategoryPrestation $categoryPrestation, Setting $setting)
    {
        $this->middleware('auth');
        $this->categoryPrestation = $categoryPrestation;
        $this->setting = $setting;
    }

    public function index()
    {
        $categories = $this->categoryPrestation->all();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('prestation.category.index', compact(['categories']));
    }

    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'name' => 'required |unique:category_prestations,name',
        ]);

        try {
            $this->categoryPrestation->create([
                "slug" => Str::slug($data['name']),
                "name" => $data['name'],
            ]);
            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }


    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->validate($request, [
            'id2' => 'required',
            'name2' => 'required',
        ]);

        try {
            $categorie = $this->categoryPrestation->find($data['id2']);
            $categorie->name = $data['name2'];
            $categorie->save();

            return back()->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }


    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $categoryPrestation = $this->categoryPrestation->find($id)->delete();

        if ($categoryPrestation) {
            return back()->with('success', "    Un élement a été supprimé ! ");
        } else {
            return back()->with('error', "    Element utilisé ailleurs ! ");
        }
    }


    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-category-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->categoryPrestation->find($id);
        return response()->json($data);
    }
}
