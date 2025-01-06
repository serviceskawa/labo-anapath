<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategorie;
use App\Models\Setting;
use Illuminate\Http\Request;

class ExpenseCategorieController extends Controller
{
    protected $expenseCategorie;
    protected $setting;
        public function __construct(ExpenseCategorie $expenseCategorie, Setting $setting){
        $this->expenseCategorie = $expenseCategorie;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     // if (!getOnlineUser()->can('view-expense_categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $expenseCategories = $this->expenseCategorie->latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('expenses_categorie.index',compact(['expenseCategories']));
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
        // if (!getOnlineUser()->can('create-expense_categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        try {
                ExpenseCategorie::create([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseCategorie  $expenseCategorie
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategorie $expenseCategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseCategorie  $expenseCategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(ExpenseCategorie $expenseCategorie)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseCategorie  $expenseCategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseCategorie $expenseCategorie)
    {
        // if (!getOnlineUser()->can('edit-expense_categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        try {
                $expenseCategorie->update([
                    'name' => $request->name,
                    'description' => $request->description,
                ]);

                $expenseCategorie->save();
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseCategorie  $expenseCategorie
     * @return \Illuminate\Http\Response
     */
    public function delete($expenseCategorie)
    {
        // if (!getOnlineUser()->can('delete-expense_categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $expenseCategorie = $this->expenseCategorie->find($expenseCategorie)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
