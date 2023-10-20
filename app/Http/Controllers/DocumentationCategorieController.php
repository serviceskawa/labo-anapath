<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use App\Models\DocumentationCategorie;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentationCategorieController extends Controller
{
    protected $documentationCategorie;
    protected $setting;
        public function __construct(DocumentationCategorie $documentationCategorie, Setting $setting){
        $this->$documentationCategorie = $documentationCategorie;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents_categories = DocumentationCategorie::all();
        // return view('documentations.categories.index', compact('documents_categories'));

        // Récupérez toutes les catégories
        $categories = DocumentationCategorie::all();

        // Retournez les catégories au format JSON
        // return response()->json($categories);
        return view('documentations.categories.index', compact('categories'));
    }


    public function getcategoriedocs()
    {
        // Récupérez toutes les catégories
        $categories = DocumentationCategorie::latest()->get();

        // Retournez les catégories au format JSON
        return response()->json($categories);
    }


    public function getdocs($id)
    {
        // Récupérez toutes les catégories
        $docs = Doc::where('documentation_categorie_id',$id)->where('user_id',Auth::user()->id)->get();

        // Retournez les catégories au format JSON
        return response()->json($docs);
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
        if (!getOnlineUser()->can('create-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $this->validate($request, [
            'name' => ['required','string','max:255'],
        ]);

        try {

                $document = new DocumentationCategorie();
                $document->name = $request->name;
                $document->save();

                 return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentationCategorie  $documentationCategorie
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentationCategorie $documentationCategorie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocumentationCategorie  $documentationCategorie
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentationCategorie $documentationCategorie)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocumentationCategorie  $documentationCategorie
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentationCategorie $documentationCategorie)
    {
        if (!getOnlineUser()->can('edit-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $this->validate($request, [
            'name' => ['required','string','max:255'],
        ]);

        // dd($documentationCategorie);
        try {

            $documentationCategorie->name = $request->name;
            $documentationCategorie->save();
            // dd($documentationCategorie->name);

                 return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentationCategorie  $documentationCategorie
     * @return \Illuminate\Http\Response
     */
    public function delete(DocumentationCategorie $documentationCategorie)
    {
        // if (!getOnlineUser()->can('delete-emloyees')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $documentationCategorie->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
