<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\CashboxTicket;
use App\Models\Expense;
use App\Models\ExpenseCategorie;
use App\Models\Setting;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $expenses_categorie = ExpenseCategorie::latest()->get();
        $cash_ticket = CashboxTicket::latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('expenses.index',compact(['expenses','expenses_categorie','cash_ticket']));   
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $expenses_categories = ExpenseCategorie::latest()->get();
        $cash_tickets = CashboxTicket::latest()->get();
        $articles = Article::latest()->get();
        $suppliers = Supplier::latest()->get();

        return view('expenses.create',compact(['expenses_categories','cash_tickets','articles','suppliers']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-expenses')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        
                if ($request->hasFile('receipt')) 
                {
                    $path = $request->file('receipt')->store('preuves', 'public');
                } else {
                    $path = null;
                }

                $search_article = Article::find($request->item_id)->first();

        try {
                Expense::create([
                    'total_amount' => $request->total_amount,
                    'user_id' => Auth::user()->id,
                    'unit_price' => $request->unit_price,
                    'expense_categorie_id' => $request->expense_categorie_id,
                    'cashbox_ticket_id' => $request->cashbox_ticket_id,
                    'paid' => $request->paid,
                    'receipt' => $path,
                    'supplier_id' => $request->supplier_id,
                    'item_id' => $request->item_id,
                    'item_name' => $search_article->article_name,
                    'quantity' => $request->quantity,
                ]);

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
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
        // if (!getOnlineUser()->can('edit-expense_categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

try {
                if ($request->hasFile('receipt')) 
                {
                    $path = $request->file('receipt')->store('preuves', 'public');
                } else {
                    $path = null;
                }

                $expense->update([
                    'amount' => $request->amount,
                    'user_id' => Auth::user()->id,
                    'description' => $request->description,
                    'expense_categorie_id' => $request->expense_categorie_id,
                    'cashbox_ticket_id' => $request->cashbox_ticket_id,
                    'paid' => $request->paid,
                    'receipt' => $path
                ]);

                $expense->save();

                return back()->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
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