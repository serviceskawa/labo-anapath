<?php

namespace App\Http\Controllers;

use App\Models\RefundRequest;
use App\Models\Setting;
use App\Models\TestOrder;
use Illuminate\Http\Request;

class RefundRequestController extends Controller
{

    protected $setting;
    protected $refundRequest;

    public function __construct(Setting $setting, RefundRequest $refundRequest)
    {
        $this->setting = $setting;
        $this->refundRequest = $refundRequest;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = $this->setting->find(1);
        $refundRequests = $this->refundRequest->latest()->get();

        return view('errors_reports.refund.index', compact('setting', 'refundRequests'));
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
            'test_order_code'=>'required',

            'montant'=>'required',

            'description'=>'required',
        ]);

        $test_order = TestOrder::where('code',$data['test_order_code'])->first();
        try {
            $this->refundRequest->create([
                'test_order_id'=>$test_order->id,
                'montant'=>$data['montant'],
                'description'=>$data['description'],
            ]);
            return back()->with('success',"Catégrie enregistrée avec success");
        } catch (\Throwable $th) {
            return back()->with('error',"Un problème est suvenu lors de l'enrégistrement");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function show(RefundRequest $refundRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $refundRequest = $this->refundRequest->find($id);
        return response()->json(['data'=>$refundRequest,'code'=>$refundRequest->order->code]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $this->validate($request,[
            'test_order_code'=>'required',

            'montant'=>'required',

            'description'=>'required',

            'id'=>'required',
        ]);
        $test_order = TestOrder::where('code',$data['test_order_code'])->first();
        try {
            $refundRequest = $this->refundRequest->find($data['id']);
            $refundRequest->update([
                'test_order_id'=>$test_order->id,
                'montant'=>$data['montant'],
                'description'=>$data['description'],
            ]);
            return back()->with('success',"Mis à jour éffectué avec success");
        } catch (\Throwable $th) {
            return back()->with('error',"Erreur d'enrégistrement");
        }
    }

    public function updateStatus(Request $request)
    {
        $data = $this->validate($request,[
            'status'=>'nullable',
            'id'=>'required',
        ]);
        try {
            $refundRequest = $this->refundRequest->find($data['id']);
            $refundRequest->update([
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
     * @param  \App\Models\RefundRequest  $refundRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $refundRequest = $this->refundRequest->find($id);
        $refundRequest->delete();
        return back()->with('success',"Suppression éffectuée avec success");
    }
}
