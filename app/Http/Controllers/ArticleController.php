<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Movement;
use App\Models\Setting;
use App\Models\UnitMeasurement;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $article;
    protected $setting;



    public function __construct(Article $article, Setting $setting){
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
        // if (!getOnlineUser()->can('view-articles')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $articles = $this->article->latest()->get();
        $units = UnitMeasurement::latest()->get();
        $movs = Movement::latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('articles.index',compact(['articles','units','movs']));
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
        // if (!getOnlineUser()->can('create-articles')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        if($request->quantity_in_stock < $request->minimum)
        {
            return back()->with('error', "Échec de l'enregistrement, la quantite en stock est inferieur a la quantite minimale ! ");
        }

        $data = [
            'article_name' => $request->article_name,
            'description' => $request->description,
            'quantity_in_stock' => $request->quantity_in_stock,
            'unit_measurement_id' => $request->unit_measurement_id,
            'expiration_date' => $request->expiration_date,
            'lot_number'=>$request->lot_number,
            'minimum'=>$request->minimum,
            'prix'=>$request->prix,
        ];
     
        try {
                Article::create([
                    'article_name' => $request->article_name,
                    'description' => $request->description,
                    'quantity_in_stock' => $request->quantity_in_stock,
                    'unit_measurement_id' => $request->unit_measurement_id,
                    'expiration_date' => $request->expiration_date,
                    'lot_number'=>$request->lot_number,
                    'minimum'=>$request->minimum,
                    'prix'=>$request->prix,
                ]);
                
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit($article)
    {
        // if (!getOnlineUser()->can('edit-articles')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $article = $this->article->find($article);
        try{
            return view('articles.edit', compact('article'));
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Article $article)
    {
        // if (!getOnlineUser()->can('edit-articles')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        if($request->quantity_in_stock < $request->minimum)
        {
            return back()->with('error', "Échec de l'enregistrement, la quantite en stock est inferieur a la quantite minimale ! ");
        }

        try {
                $article->update([
                    'article_name' => $request->article_name,
                    'description' => $request->description,
                    'quantity_in_stock' => $request->quantity_in_stock,
                    'unit_measurement_id' => $request->unit_measurement_id,
                    'expiration_date' => $request->expiration_date,
                    'lot_number' => $request->lot_number,
                    'minimum' => $request->minimum,
                    'prix'=>$request->prix,
                ]);

                $article->save();
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function delete($article)
    {
        // if (!getOnlineUser()->can('delete-articles')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $article = $this->article->find($article)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }

    public function getArticle(Request $request)
    {
        $articles = $this->article->where('article_name','like','%'.$request->term.'%')->get();
        $result = $articles->map(function ($article) {
            return $article->article_name;
        });
        return response()->json($result);
    }
}

?>
