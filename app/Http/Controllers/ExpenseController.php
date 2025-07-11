<?php

namespace App\Http\Controllers;

use App\Models\Article;

use App\Models\Cashbox;
use App\Models\CashboxAdd;
use App\Models\CashboxTicket;
use App\Models\ExpenceDetail;
use App\Models\Expense;
use App\Models\ExpenseCategorie;
use App\Models\Movement;
use App\Models\Setting;
use App\Models\Supplier;
use Carbon\Carbon;
use Faker\Core\DateTime as CoreDateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    protected $expense;
    protected $expenseDetail;
    protected $article;
    protected $supplier;
    protected $setting;

    public function __construct(Expense $expense, Setting $setting, ExpenceDetail $expenseDetail, Article $article, Supplier $supplier)
    {
        $this->expense = $expense;
        $this->expenseDetail = $expenseDetail;
        $this->supplier = $supplier;
        $this->article = $article;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-expenses')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $expenses = $this->expense->latest()->get();
        $expenses_categorie = ExpenseCategorie::latest()->get();
        $cash_ticket = CashboxTicket::latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('expenses.index', compact(['expenses', 'expenses_categorie', 'cash_ticket']));
    }

    public function detail_index($id)
    {
        if (!getOnlineUser()->can('view-expence-details')) {
            return back()->with('error', "Vous n\'êtes pas autorisé");
        }

        $expense = $this->expense->find($id);
        $suppliers = $this->supplier->latest()->get();
        $expenses_categorie = ExpenseCategorie::latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('expenses.show', compact(['expense', 'suppliers', 'expenses_categorie']));
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

        return view('expenses.create', compact(['expenses_categories', 'cash_tickets', 'articles', 'suppliers']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!getOnlineUser()->can('create-expenses')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('preuves', 'public');
        } else {
            $path = null;
        }

        //                 $search_article = Article::find($request->item_id)->first();

        //         try {
        //                 Expense::create([
        //                     'total_amount' => $request->total_amount,
        //                     'user_id' => Auth::user()->id,
        //                     'unit_price' => $request->unit_price,
        //                     'expense_categorie_id' => $request->expense_categorie_id,
        //                     'cashbox_ticket_id' => $request->cashbox_ticket_id,
        //                     'paid' => $request->paid,
        //                     'receipt' => $path,
        //                     'supplier_id' => $request->supplier_id,
        //                     'item_id' => $request->item_id,
        //                     'item_name' => $search_article->article_name,
        //                     'quantity' => $request->quantity,
        // =======
        !empty($request->supplier) ? ($supplier = $this->supplier->where('name', 'like', $request->supplier)->first()) : ($supplier = null);

        if ($supplier) {
            $data['supplier_id'] = $supplier->id;
        } else {
            $supplierCreate = $this->supplier->create([
                'name' => $request->supplier,
            ]);
            $data['supplier_id'] = $supplierCreate->id;
        }
        try {
            $expense = Expense::create([
                // 'amount' => $request->amount,
                'user_id' => Auth::user()->id,
                'description' => $request->description,
                'expense_categorie_id' => $request->expense_categorie_id,
                'invoice_number' => $request->invoice_number,
                'amount' => $request->amount,
                'supplier_id' => $data['supplier_id'],
                'user_id' => Auth::user()->id,
                // 'cashbox_ticket_id' => $request->cashbox_ticket_id,
                // 'paid' => $request->paid,
                // 'receipt' => $path
            ]);

            return redirect()
                ->route('expense.details.index', $expense->id)
                ->with('success', ' Opération effectuée avec succès  ! ');
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex);
        }
    }

    public function detail_store(Request $request)
    {
        if (!getOnlineUser()->can('create-expence-details')) {
            return back()->with('error', "Vous n\'êtes pas autorisé");
        }
        $data = [
            'expense_id' => $request->expense_id,
            'article_name' => $request->article_name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'line_amount' => $request->unit_price * $request->quantity,
        ];

        // return response()->json($data);
        $article = $this->article->where('article_name', '=', $data['article_name'])->first();

        try {
            DB::transaction(function () use ($data, $article) {
                $details = new ExpenceDetail();
                $details->expense_id = $data['expense_id'];
                $details->article_name = $data['article_name'];
                $details->article_id = $article ? $article->id : null;
                $details->unit_price = $data['unit_price'];
                $details->quantity = $data['quantity'];
                $details->line_amount = $data['line_amount'];
                $details->save();
            });
            $expense = $this->expense->find($data['expense_id']);
            // $expense->amount += $data['line_amount'];
            // $expense->save();
            return response()->json($expense, 200);
            // return back()->with('success', "Bon de caisse enregistré");
        } catch (\Throwable $ex) {
            return response()->json("Échec de l'enregistrement ! " . $ex->getMessage(), 500);
        }
    }

    public function updateTotal(Request $request)
    {
        // if (!getOnlineUser()->can('edit-test-orders')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $ticket = $this->expense->findorfail($request->expense_id);

        $ticket
            ->fill([
                'amount' => $request->amount,
            ])
            ->save();

        return response()->json($ticket);
    }

    public function detail_destroy($id)
    {
        // if (!getOnlineUser()->can('delete-banks')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $detail = $this->expenseDetail->find($id);
        $ticket = $this->expense->find($detail->expense_id);
        $ticket->amount -= $detail->line_amount;
        $ticket->save();
        $detail->delete();
        return back()->with('success', 'Un élement a été supprimé !');
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

    public function getExpenceDetail($id)
    {
        $expense = $this->expense->find($id);
        $details = $this->expenseDetail->where('expense_id', $expense->id)->get();
        return response()->json($details);
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
    public function update(Request $request)
    {
        // if (!getOnlineUser()->can('edit-expense_categories')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        $expense = $this->expense->find($request->expense_id);

        try {
            if ($request->hasFile('receipt')) {
                $path = $request->file('receipt')->store('preuves', 'public');
            } else {
                $path = null;
            }

            $expense->update([
                'user_id' => Auth::user()->id,
                'description' => $request->description,
                'expense_categorie_id' => $request->expense_categorie_id,
                'supplier_id' => $request->supplier_id,
                'date' => $request->date,
                'payment' => $request->payment,
                'amount' => $request->amount,
                'invoice_number' => $request->invoice_number,
                // 'cashbox_ticket_id' => $request->cashbox_ticket_id,
                'paid' => $request->paid,
                'receipt' => $path == null ? $expense->receipt : $path,
            ]);

            $expense->save();

            // dd($expense);

            // if ($expense->paid=1) {
            //     $cash = Cashbox::find(1);
            //     $cash->current_balance -= $expense->amount;
            //     $cash->save();

            //     CashboxAdd::create([
            //         'cashbox_id' => 1,
            //         'date' => Carbon::now(),
            //         'amount' => $expense->amount,
            //         'user_id' => Auth::user()->id
            //     ]);
            //     $details = $expense->details()->get();
            //     foreach ($details as $key => $detail) {
            //         $article = $this->article->where('article_name',$detail->item_name)->first();
            //         if (!empty($article)) {
            //             $article->quantity_in_stock += $detail->quantity;
            //             $article->save();
            //             Movement::create([
            //                 'movement_type' => 'augmenter',
            //                 'date_mouvement' => Carbon::now()->format('d/m/y'),
            //                 'quantite_changed' => $detail->quantity,
            //                 'description' => '',
            //                 'article_id' => $article->id,
            //                 'user_id' => Auth::user()->id
            //             ]);

            //         }
            //     }
            // }

            return redirect()
                ->route('all_expense.index')
                ->with('success', ' Mise à jour effectuée avec succès  ! ');
        } catch (\Throwable $ex) {
            return back()->with('error', "Échec de l'enregistrement ! " . $ex->getMessage());
        }
    }

    public function expense_paid($id)
    {
        $expense = $this->expense->find($id);
        try {
            $expense->paid = 1;
            $expense->save();
            $cash = Cashbox::find(1);
            $cash->current_balance -= $expense->amount;
            $cash->save();

            CashboxAdd::create([
                'cashbox_id' => 1,
                'date' => Carbon::now(),
                'amount' => $expense->amount,
                'user_id' => Auth::user()->id,
            ]);
            // return back()->with('success', 'Dépense marquée comme payéé');
            return response()->json(200);
        } catch (\Throwable $th) {
            return back()->with('error', 'Échec de la mise à jour ! ' . $th->getMessage());
        }
    }

    public function update_stock_mouv($id)
    {
        $expense = $this->expense->find($id);
        try {
            if ($expense->paid == 1) {
                $expense->paid = 2;
                $expense->save();
                $details = $expense->details()->get();
                foreach ($details as $key => $detail) {
                    $article = $this->article->where('article_name', $detail->article_name)->first();
                    if (!empty($article)) {
                        $article->quantity_in_stock += $detail->quantity;
                        $article->save();
                        Movement::create([
                            'movement_type' => 'augmenter',
                            'date_mouvement' => Carbon::now()->format('d/m/y'),
                            'quantite_changed' => $detail->quantity,
                            'description' => '',
                            'article_id' => $article->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            } else {
                $expense->paid = 2;
                $expense->save();
                $cash = Cashbox::find(1);
                $cash->current_balance -= $expense->amount;
                $cash->save();

                CashboxAdd::create([
                    'cashbox_id' => 1,
                    'date' => Carbon::now(),
                    'amount' => $expense->amount,
                    'user_id' => Auth::user()->id,
                ]);
                $details = $expense->details()->get();
                foreach ($details as $key => $detail) {
                    $article = $this->article->where('article_name', $detail->article_name)->first();
                    if (!empty($article)) {
                        $article->quantity_in_stock += $detail->quantity;
                        $article->save();
                        Movement::create([
                            'movement_type' => 'augmenter',
                            'date_mouvement' => Carbon::now()->format('d/m/y'),
                            'quantite_changed' => $detail->quantity,
                            'description' => '',
                            'article_id' => $article->id,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            }

            // return back()->with('success', 'Stock mis à jour');
            return response()->json(200);
        } catch (\Throwable $th) {
            return back()->with('error', 'Échec de la mise à jour ! ' . $th->getMessage());
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

        return back()->with('success', ' Elément supprimé avec succès  ! ');
    }
}
