<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Setting;
use App\Models\signal;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SignalController extends Controller
{

    protected $setting;
    protected $signal;

    public function __construct(Setting $setting, signal $signal)
    {
        $this->middleware('auth');
         $this->setting = $setting;
         $this->signal = $signal;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::where('branch_id', session('selected_branch_id'))->first();
        $signals = $this->signal->latest()->get();


        return view('examens.signals.index',compact('setting','signals'));
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

        $user = Auth::user();
        $data = $this->validate($request, [
            'test_order_code'=>'required',
            'type_signal'=>'required',
            'commentaire'=>'required',

        ]);
        $test_order_id = TestOrder::where('code',$data['test_order_code'])->first();

        try {
            signal::create([
                'user_id'=>$user->id,
                'test_order_id'=>$test_order_id->id,
                'type_signal'=>$data['type_signal'],
                'commentaire'=>$data['commentaire']
            ]);
            return redirect()->route('signals.index')->with('success', "Votre demande a été enrégistrées");
        } catch (\Throwable $th) {
            return back()->with('erreur', "Erreur d'enregistrement");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\signal  $signal
     * @return \Illuminate\Http\Response
     */
    public function show(signal $signal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\signal  $signal
     * @return \Illuminate\Http\Response
     */
    public function edit(signal $signal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\signal  $signal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, signal $signal)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\signal  $signal
     * @return \Illuminate\Http\Response
     */
    public function destroy(signal $signal)
    {
        //
    }
}
