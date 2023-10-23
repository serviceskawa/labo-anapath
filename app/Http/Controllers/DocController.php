<?php

namespace App\Http\Controllers;

use App\Events\ShareDocEvent;
use App\Models\Doc;
use App\Models\DocumentationCategorie;
use App\Models\DocVersion;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $all_docs = true;


        return view('documentations.docs.index', compact('docs','categories','all_docs'));
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

    public function getAllVersion($id)
    {
        $doc_version = DocVersion::where('doc_id',$id)->get();
        return response()->json($doc_version);
    }

    public function getUserDoc($id)
    {
        $doc_version = DocVersion::find($id);
        $user = User::find($doc_version->user_id);
        return response()->json($user->fullname());
    }

    /**
     * Store a newly created resource in storage.
     * Pour créer une nouvelle version du même document
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
            'first_doc_id' => 'integer',
            'attachment' => 'required'
        ]);

        if ($request->hasFile('attachment'))
        {
            $imagePath = $request->file('attachment')->store('documents','public');
            $fileSize =  $request->file('attachment')->getSize();
        }


        try {
                // $doc = new Doc();
                // $doc->title = $request->title;
                // $doc->attachment = $imagePath;
                // $doc->is_current_version = 1;
                // $doc->documentation_categorie_id = $request->category_id;
                // $doc->file_size = $fileSize;
                // $doc->user_id = auth()->user()->id;
                // $doc->save();
                $lastVersion = DocVersion::where('doc_id',$request->first_doc_id)->latest()->first();

                $doc_version = new DocVersion();
                $doc_version->title = $request->title;
                $doc_version->doc_id = $request->first_doc_id;
                $doc_version->version = $lastVersion->version +1;
                $doc_version->file_size = $fileSize;
                $doc_version->attachment = $imagePath;
                $doc_version->user_id = auth()->user()->id;
                $doc_version->save();

                // dd($request->category_id);
                // dd('ok');

                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ".$ex->getMessage());
            }

    }



    public function doc_share()
    {
        $docs = [];
        $user = User::find(Auth::user()->id);
        $doc_all = Doc::all();
        foreach ($doc_all as $key => $doc) {
            if ($user->userCheckRole($doc->role_id)) {
                $docs []=$doc;
            }
        }
        $categories = DocumentationCategorie::latest()->get();
        $all_docs = false;

        return view('documentations.docs.index', compact('docs','categories','all_docs'));
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
            $fileSize =  $request->file('attachment')->getSize();
        }else{
            $imagePath = null;
        }




        try {
            $doc = new Doc();
            $doc->title = $request->title;
            $doc->attachment = $imagePath;
            $doc->is_current_version = 1;
            $doc->documentation_categorie_id = $request->documentation_categorie_id;
            $doc->file_size = $fileSize;
            $doc->user_id = auth()->user()->id;
            $doc->save();

            $doc_version = new DocVersion();
            $doc_version->doc_id = $doc->id;
            $doc_version->title = $request->title;
            $doc_version->attachment = $imagePath;
            $doc_version->user_id = auth()->user()->id;
            $doc_version->version = 1;
            $doc_version->save();

            // dd($request->category_id);
            // dd('ok');
                return back()->with('success', " Opération effectuée avec succès  ! ");
            } catch(\Throwable $ex){
                return back()->with('error', "Échec de l'enregistrement ! ".$ex->getMessage());
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
    public function update(Request $request)
    {
        if (!getOnlineUser()->can('edit-employees')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $doc = Doc::find($request->doc_id);

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
