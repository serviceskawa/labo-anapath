<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Setting;
use App\Models\SupplierCategorie;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    protected $setting;
    protected $suppliers;
    protected $categories;
    public function __construct(Setting $setting, Supplier $suppliers,SupplierCategorie $categories){
        $this->setting = $setting;
        $this->suppliers = $suppliers;
        $this->categories = $categories;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $suppliers = $this->suppliers->latest()->get();
        $categories = $this->categories->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        // dd($testcategories);
        return view('suppliers.index',compact(['suppliers','categories']));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSupplierRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }


        // Récupérer les données saisir par l'utilisateur et qui respectent les conditions
        $supplierData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'information' => $request->information,
            'supplier_category_id' => $request->supplier_category_id,
        ];


        // insérer les données dans la base de données
        try {
            Supplier::create($supplierData);
            return back()->with('success', "Un Fournisseur enregistré ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // if (!getOnlineUser()->can('edit-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $supplierData = $this->suppliers->find($id);
        return response()->json($supplierData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupplierRequest  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('edit-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $supplierData=[
            'id' => $request->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'information' => $request->information,
            'supplier_category_id' => $request->supplier_category_id,
        ];



        try {

            $supplier = Supplier::find($supplierData['id']);
            $supplier->name = $supplierData['name'];
            $supplier->email = $supplierData['email'];
            $supplier->phone = $supplierData['phone'];
            $supplier->address = $supplierData['address'];
            $supplier->information = $supplierData['information'];
            $supplier->supplier_category_id = $supplierData['supplier_category_id'];
            $supplier->save();

            return back()->with('success', "Les information d'un fournisseur ont été mis à jour ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if (!getOnlineUser()->can('delete-suppliers')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        try {
            $this->suppliers->find($id)->delete();
            return back()->with('success', "    Un élement a été supprimé ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Impossible de supprimer cet élément !  Celui-ci est lié à d'autres éléments. Pour effectuer cette suppression, vous devez d'abord supprimer ou mettre à jour les éléments liés dans d'autres tables.");
        }
    }
}
