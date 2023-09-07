<?php

namespace App\Http\Controllers;

use App\Http\Middleware\Authenticate;
use App\Models\Movement;
use App\Models\Article;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovementController extends Controller
{
    protected $article;
    protected $movement;
    protected $setting;

    public function __construct(Article $article, Setting $setting, Movement $movement){
        $this->article = $article;
        $this->setting = $setting;
        $this->movement = $movement;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (!getOnlineUser()->can('view-movements')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }
        
        $movements = $this->movement->latest()->get();
        $articles = $this->article->latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('movements.index',compact(['movements','articles']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!getOnlineUser()->can('create-movements')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        try {
                $a = Article::find($request->article_id);

                if ($request->movement_type == 'ajouter') {
                $a->update([
                    'quantity_in_stock' => $request->quantite_changed + $a->quantity_in_stock,
                ]);
                }elseif($request->movement_type == 'diminuer')
                    {
                        if($a->quantity_in_stock < $request->quantite_changed)
                        {
                            return back()->with('error', "Échec de l'enregistrement, la quantite en stock est inferieur a la quantite a diminuer ! ");
                        }
                        
                        $a->update([
                            'quantity_in_stock' => $a->quantity_in_stock - $request->quantite_changed,
                        ]);
                    }

                    Movement::create([
                        'movement_type' => $request->movement_type,
                        'date_mouvement' => Carbon::now()->format('d/m/y'),
                        'quantite_changed' => $request->quantite_changed,
                        'description' => $request->description,
                        'article_id' => $request->article_id,
                        'user_id' => Auth::user()->id
                    ]);

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show(Movement $movement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function edit(Movement $movement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movement $movement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movement $movement)
    {
        //
    }
}