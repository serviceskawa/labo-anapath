<?php

namespace App\Http\Controllers;

use App\Models\ProblemCategory;
use App\Models\Setting;
use Illuminate\Http\Request;

class ProblemCategoryController extends Controller
{
    protected $setting;
    protected $problemCategory;

    public function __construct(Setting $setting, ProblemCategory $problemCategory)
    {
        $this->setting = $setting;
        $this->problemCategory = $problemCategory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $setting = $this->setting->find(1);
        $problemCategories = $this->problemCategory->latest()->get();

        return view('errors_reports.categorie.index', compact('setting', 'problemCategories'));

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
        $data = $this->validate($request,[
            'name'=>'required'
        ]);

        try {
            $this->problemCategory->create($data);
            return back()->with('success',"Catégrie enregistrée avec success");
        } catch (\Throwable $th) {
            return back()->with('error',"Un problème est suvenu lors de l'enrégistrement");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProblemCategory  $problemCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ProblemCategory $problemCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProblemCategory  $problemCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $problemCategory = $this->problemCategory->find($id);
        return response()->json($problemCategory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProblemCategory  $problemCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validate($request,[
            'name'=>'required',
            'id'=>'required'
        ]);
        try {
            $problemCategory = $this->problemCategory->find($data['id']);
            $problemCategory->update([
                'name'=>$data['name']
            ]);
            return back()->with('success',"Mis à jour éffectué avec success");
        } catch (\Throwable $th) {
            return back()->with('error',"Erreur d'enrégistrement");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProblemCategory  $problemCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $problemCategory = $this->problemCategory->find($id);
        $problemCategory->delete();
        return back()->with('success',"Suppression éffectuée avec success");
    }
}
