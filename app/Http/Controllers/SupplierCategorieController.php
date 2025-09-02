<?php

namespace App\Http\Controllers;

use App\Models\SupplierCategorie;
use App\Http\Requests\StoreSupplierCategorieRequest;
use App\Http\Requests\UpdateSupplierCategorieRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SupplierCategorieController extends Controller
{

    protected $setting;
    protected $categories;
    public function __construct(Setting $setting, SupplierCategorie $categories){
        $this->setting = $setting;
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-supplier-categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $supplierCategories = $this->categories->latest()->get();
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        config(['app.name' => $setting->titre]);
        return view('suppliers.category.index',compact(['supplierCategories']));
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
     * @param  \App\Http\Requests\StoreSupplierCategorieRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-supplier-categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data= [
            'name' => $request->name,
            'description' => $request->description ? $request->description : ''
        ];

        try {
            $category = $this->categories->create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SupplierCategorie  $supplierCategorie
     * @return \Illuminate\Http\Response
     */
    public function show(SupplierCategorie $supplierCategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SupplierCategorie  $supplierCategorie
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!getOnlineUser()->can('edit-supplier-categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data = $this->categories->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierCategorieRequest  $request
     * @param  \App\Models\SupplierCategorie  $supplierCategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('edit-supplier-categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $data= [
            'id' => $request->id,
            'name' => $request->name,
            'description' => $request->description,
        ];

        try {
           $categorie = $this->categories->find($data['id']);
           $categorie->name = $data['name'];
           $categorie->description = $data['description'];
           $categorie->save();

            return back()->with('success', " Mise à jour effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SupplierCategorie  $supplierCategorie
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-supplier-categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        try {
            $this->categories->find($id)->delete();
            return back()->with('success', "    Un élement a été supprimé ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Impossible de supprimer cet élément !  Celui-ci est lié à d'autres éléments. Pour effectuer cette suppression, vous devez d'abord supprimer ou mettre à jour les éléments liés dans d'autres tables.");
        }

    }
}
