<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Setting;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $expense;
    protected $setting;

    public function __construct(Expense $expense, Setting $setting){
        $this->expense = $expense;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-expenses')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        
        $expenses = $this->expense->latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('expenses.index',compact(['expenses']));   
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function edit(Expense $expense)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function delete($expense)
    {
        // if (!getOnlineUser()->can('delete-expenses')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $expense = $this->expense->find($expense)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}