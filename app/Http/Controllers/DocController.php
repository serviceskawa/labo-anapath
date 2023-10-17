<?php

namespace App\Http\Controllers;

use App\Events\ShareDocEvent;
use App\Models\Doc;
use App\Models\DocumentationCategorie;
use App\Models\Role;
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

    public function detail_index($categorie)
    {
        if (!getOnlineUser()->can('create-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $cat = Doc::where('documentation_categorie_id', $categorie)->get();
        return view('documentations.docs.index_detail', compact('cat'));
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
            'category_id' => 'integer',
            'attachment' => 'required'
        ]);

        if ($request->hasFile('attachment'))
        {
            $imagePath = $request->file('attachment')->store('documents','public');
        }


        try {
                $doc = new Doc();
                $doc->title = $request->title;
                $doc->attachment = $imagePath;
                $doc->is_current_version = 1;
                $doc->documentation_categorie_id = $request->category_id;
                $doc->user_id = auth()->user()->id;
                $doc->save();
                // dd($request->category_id);
                // dd('ok');

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }

    }



     public function store_fichier(Request $request)
    {
        if (!getOnlineUser()->can('create-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $this->validate($request,[
            'title' => 'required|string|max:255',
            'documentation_categorie_id' => 'integer',
            'attachment' => 'required'
        ]);

        if ($request->hasFile('attachment'))
        {
            $imagePath = $request->file('attachment')->store('documents','public');
        }else{
            $imagePath = null;
        }

        try {
            $doc = new Doc();
            $doc->title = $request->title;
            $doc->attachment = $imagePath;
            $doc->is_current_version = 1;
            $doc->documentation_categorie_id = $request->documentation_categorie_id;
            $doc->user_id = auth()->user()->id;
            $doc->save();
            // dd($request->category_id);
            // dd('ok');
                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ");
            }

    }

    public function sharedocs(Request $request)
    {
        // dd($request);
        $doc_id = $request->doc_id;
        $role_id = $request->role_id;
        $role = Role::find($role_id);

        $doc = Doc::find($doc_id);
        $doc->update([
            'role_id' => $role_id
        ]);

        foreach (getUsersByRole($role->slug) as  $user) {
           event(new ShareDocEvent($user,$doc));
        }

        return back()->with('success', " Opération effectuée avec succès  ! ");

    }

    public function getrecents()
    {
        // Récupérez toutes les fichiers recent
        $recentfiles = Doc::latest()->limit(3)->get();
        // dd($recentfiles);
        // Retournez les catégories au format JSON
        return response()->json($recentfiles);
    }


    public function getfiledelete()
    {
        // Récupérez toutes les fichier supprimes
        $deletedFiles = Doc::onlyTrashed()->get();

        // dd($recentfiles);
        // Retournez les catégories au format JSON
        return response()->json($deletedFiles);
    }

    public function supprimer(Doc $doc)
    {
        $doc->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
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
        if (!getOnlineUser()->can('edit-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }

        $this->validate($request,[
            'title' => 'required|string|max:255'
        ]);

        if ($request->hasFile('attachment'))
        {
            $imagePath = $request->file('attachment')->store('documents','public');
        }else{
            $imagePath = $doc->attachment;
        }

        try
        {
            // dd($request->title);
            $doc->title = $request->title;
            $doc->attachment = $imagePath;
            $doc->save();

            return back()->with('success', " Opération effectuée avec succès  ! ");
        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! ");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Doc  $doc
     * @return \Illuminate\Http\Response
     */
    public function delete(Doc $doc)
    {
        // if (!getOnlineUser()->can('delete-emloyees')) {
        //     return back()->with('error', "Vous n'êtes pas autorisé");
        // }

        $doc->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }
}
