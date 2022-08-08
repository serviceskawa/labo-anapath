<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Doctor;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\DetailTestOrder;
use App\Models\Patient;
use App\Models\Hospital;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestOrderController extends Controller
{

    public function index(){

        $examens = TestOrder::with(['patient'])->get();
        $contrats = Contrat::all();
        $patients = Patient::all();
        $doctors  = Doctor::all();
        //$tests = Test::all();
        $hopitals = Hospital::all();
        // dd($examens);
        return view('examens.index',compact(['examens','contrats','patients','doctors','hopitals']));
    }


    public function store(request $request){

        $data=$this->validate($request, [
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'hospital_id' => 'required',
            'reference_hopital' => 'nullable',
            'contrat_id' => 'required',

        ]);

        try {

            $test_order = new TestOrder();
            DB::transaction(function () use ($data,$test_order) {

                $test_order->contrat_id = $data['contrat_id'];
                $test_order->patient_id = $data['patient_id'];
                $test_order->hospital_id = $data['hospital_id'];
                $test_order->doctor_id = $data['doctor_id'];
                $test_order->reference_hopital = $data['reference_hopital'];
                $test_order->save();

            });

            return redirect()->route('details_test_order.index',$test_order->id);

            } catch(\Throwable $ex){

          return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }
    }


    public function create(){

        $patients = Patient::all();
        $doctors = Doctor::all();
        $hopitals = Hospital::all();
        $contrats = Contrat::ofStatus('ACTIF')->get();
        return view('examens.create',compact(['patients','doctors','hopitals','contrats']));
    }


    public function destroy($id){
        TestOrder::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }


    public function details_index($id){

        $test_order = TestOrder::find($id);

        $tests = Test::all();

        $details = DetailTestOrder::where('test_order_id',$test_order->id)->get();

        return view('examens.details.index',compact(['test_order','details','tests']));
    }


    public function details_store(Request $request){


        $data = $this->validate($request, [
            'test_order_id' => 'required',
            'test_id' => 'required',
            'price' => 'required |numeric',
            'discount' => 'required |numeric',
            'total' => 'required |numeric',
        ]);

       $test = Test::find($data['test_id']);


        try {
            DB::transaction(function () use ($data,$test) {
                $details = new DetailTestOrder();
                $details->test_id = $data['test_id'];
                $details->test_name = $test->name;
                $details->price = $data['price'];
                $details->discount = $data['discount'];
                $details->total = $data['total'];
                $details->test_order_id = $data['test_order_id'];
                $details->save();
            });

            //  return back()->with('success', "Opération effectuée avec succès ! ");
            return response()->json(200);
            } catch(\Throwable $ex){
                return response()->json(200);

        //   return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }
    }

    public function getDetailsTest($id)
    {
        $test_order = TestOrder::find($id);

        $tests = Test::all();

        $details = DetailTestOrder::where('test_order_id',$test_order->id)->get();

        return response()->json($details);
    }

    public function updateTestTotal(Request $request)
    {
        $test_order = TestOrder::findorfail($request->test_order_id);

        $test_order->fill([
            "total" => $request->total,
            "subtotal" => $request->subTotal,
            "discount" => $request->discount
        ])->save();

        return response()->json($test_order);
    }

    public function details_destroy(Request $request)
    {
        $detail = DetailTestOrder::findorfail($request->id);
        $detail->delete();

        return response()->json(200);

        // return back()->with('success', "    Un élement a été supprimé ! ");
    }

    public function updateSatus(Request $request)
    {
        $test_order = TestOrder::findorfail($request->test_order_id);

        $test_order->fill(["status" => '1'])->save();

        return redirect()->route('test_order.index')->with('success', "   Examen finalisé! ");
    }
}
