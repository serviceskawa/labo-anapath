<?php

namespace App\Http\Controllers;

use App\Models\Doc;
use App\Models\DocumentationCategorie;
use App\Models\Setting;
use Illuminate\Http\Request;

class DocController extends Controller
{
    protected $doc;
    protected $setting;
        public function __construct(Doc $doc, Setting $setting){
        $this->$doc = $doc;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = DocumentationCategorie::latest()->get();
        $docs = Doc::all();
        return view('documentations.docs.index', compact('docs','categories'));
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

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'description' => 'string|max:255',
            'documentation_categorie_id' => 'integer',
            'attachment' => 'required'
        ]);

        if ($request->hasFile('attachment')) 
        {
            $imagePath = $request->file('attachment')->store('documents','public');
        }
         
        try {          
                $doc = new Doc();
                $doc->title = $request->title;
                $doc->description = $request->description;
                $doc->attachment = $imagePath;
                $doc->is_current_version = 1;
                $doc->documentation_categorie_id = $request->documentation_categorie_id;
                $doc->user_id = auth()->user()->id;
                $doc->save(); 

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function show(Doc $doc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function edit(Doc $doc)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doc $doc)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doc $doc)
    {
        //
    }
}