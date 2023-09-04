<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Setting;
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
        if (!getOnlineUser()->can('view-articles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        
        $articles = $this->article->latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('articles.index',compact(['articles']));
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
        if (!getOnlineUser()->can('create-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'article_name' => $request->article_name,
            'description' => $request->description,
            'quantity_in_stock' => $request->quantity_in_stock,
            'unit_of_measurement' => $request->unit_of_measurement,
            'expiration_date' => $request->expiration_date,
            'lot_number'=>$request->lot_number,
            'minimum'=>$request->minimum,
        ];

        try {
            $this->article->create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
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
         if (!getOnlineUser()->can('edit-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $article = $this->article->find($article);
        try{
            return view('articles.edit', compact('article'))->with('success', " Mise à jour effectuée avec succès  ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$article)
    {
        if (!getOnlineUser()->can('edit-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        
        $data=[
            'id' => $request->id,
            'article_name' => $request->article_name,
            'description' => $request->description,
            'quantity_in_stock' => $request->quantity_in_stock,
            'unit_of_measurement' => $request->unit_of_measurement,
            'expiration_date' => $request->expiration_date,
            'lot_number'=>$request->lot_number,
            'minimum'=>$request->minimum,
        ];

        try {
            $article = $this->article->find($data['id']);

            $article->article_name = $data['article_name'];
            $article->description = $data['description'];
            $article->quantity_in_stock = $data['quantity_in_stock'];
            $article->unit_of_measurement = $data['unit_of_measurement'];
            $article->expiration_date = $data['expiration_date'];
            $article->lot_number = $data['lot_number'];
            $article->minimum = $data['minimum'];

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
        if (!getOnlineUser()->can('delete-articles')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $article = $this->article->find($article)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}

?>