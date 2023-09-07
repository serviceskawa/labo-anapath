<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Models\Test;
use App\Models\Contrat;
use App\Models\CategoryTest;
use App\Models\Details_Contrat;
use App\Models\Setting;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $test;
    protected $categoryTest;
    protected $contrat;
    protected $detailsContrat;
    protected $setting;

    public function __construct(Test $test, CategoryTest $categoryTest, Contrat $contrat, Details_Contrat $detailsContrat, Setting $setting){
        $this->test = $test;
        $this->categoryTest = $categoryTest;
        $this->contrat = $contrat;
        $this->detailsContrat = $detailsContrat;
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!getOnlineUser()->can('view-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $tests = $this->test->latest()->get();

        $categories = $this->categoryTest->latest()->get();

        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);

        return view('tests.index',compact(['tests','categories']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestRequest $request)
    {
        if (!getOnlineUser()->can('create-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = [
            'price' => $request->price,
            'name' => $request->name,
            'category_test_id'=>$request->category_test_id,
        ];

        try {
            $this->test->create($data);
            return back()->with('success', " Opération effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!getOnlineUser()->can('edit-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data = $this->test->find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function update(TestRequest $request)
    {
        if (!getOnlineUser()->can('edit-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $data=[
            'id' => $request->id,
            'price' => $request->price,
            'name' => $request->name,
            'category_test_id'=>$request->category_test_id,
        ];

        try {
            $test = $this->test->find($data['id']);
            $test->name = $data['name'];
            $test->price = $data['price'];
            $test->category_test_id = $data['category_test_id'];
            $test->save();
            return back()->with('success', " Mise à jour effectuée avec succès  ! ");

        } catch(\Throwable $ex){
            return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Test  $test
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!getOnlineUser()->can('delete-tests')) {
            return back()->with('error', "Vous n'êtes pas autorisé");
        }
        $test = $this->test->find($id)->delete();

        return back()->with('success', " Elément supprimé avec succès  ! ");
    }

    public function getTestAndRemise(Request $request)
    {

        $data = $this->test->find($request->testId);

        $detail = Details_Contrat::where(['contrat_id' => $request->contratId, 'category_test_id' => $request->categoryTestId])->first();
        if($detail == null){
            $detail = 0;
        }else{
            $detail = $detail->pourcentage;
        }
        return response()->json(["data"=>$data,"detail"=>$detail]);
    }
}