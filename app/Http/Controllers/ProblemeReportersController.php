<?php

namespace App\Http\Controllers;

use App\Models\ProblemCategory;
use App\Models\ProblemReport;
use App\Models\Setting;
use App\Models\TestOrder;
use Illuminate\Http\Request;

class ProblemeReportersController extends Controller
{

    protected $setting;
    protected $problemReport;
    protected $problemCategory;
    protected $testOrder;

    public function __construct(Setting $setting, ProblemReport $problemReport, ProblemCategory $problemCategory, TestOrder $testOrder)
    {
        $this->setting = $setting;
        $this->problemReport = $problemReport;
        $this->problemCategory = $problemCategory;
        $this->testOrder = $testOrder;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $setting = $this->setting->find(1);
        $problemReports = $this->problemReport->latest()->get();
        $problemCategories = $this->problemCategory->latest()->get();

        return view('errors_reports.index', compact('setting', 'problemReports','problemCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $testOrders = $this->testOrder->all();
        $problemCategories = $this->problemCategory->latest()->get();
        $setting = $this->setting->find(1);
        config(['app.name' => $setting->titre]);
        return view('errors_reports.create', compact('testOrders','problemCategories'));
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
            'test_orders_id'=>'required',

            'errorCategory_id'=>'required',

            'description'=>'required',
        ]);

        // $test_order = TestOrder::where('code',$data['test_order_code'])->first();
        try {
            $this->problemReport->create([
                'test_order_id'=>$data['test_orders_id'],
                'errorCategory_id'=>$data['errorCategory_id'],
                'description'=>$data['description'],
            ]);
            return back()->with('success',"Problème enregistrée avec success");
        } catch (\Throwable $th) {
            return back()->with('error',"Un problème est suvenu lors de l'enrégistrement");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function show(ProblemReport $problemReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $problemReport = $this->problemReport->find($id);
        return response()->json($problemReport);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validate($request,[
            'status'=>'nullable',
            'id'=>'required',
        ]);
        try {
            $problemReport = $this->problemReport->find($data['id']);
            $problemReport->update([
                'status'=>$data['status']
            ]);
            return response()->json($data['status'],200);
        } catch (\Throwable $th) {
            return response()->json(500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProblemReport  $problemReport
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $problemReport = $this->problemReport->find($id);
        $problemReport->delete();
        return back()->with('success',"Suppression éffectuée avec success");
    }
}
