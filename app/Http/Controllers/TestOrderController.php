<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Models\Doctor;
use App\Models\Contrat;
use App\Models\Details_Contrat;
use App\Models\Patient;
use App\Models\Hospital;
use App\Models\TestOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestOrderController extends Controller
{
    
    public function index(){

        $examens = TestOrder::all();
        $contrats = Contrat::all();
        $patients = Patient::all();
        $docteurs  = Doctor::all();
        $tests = Test::all();
        $hopitals = Hospital::all();
        return view('examens.index',compact(['examens','contrats','patients','docteurs','tests','hopitals']));
    }


    public function store(request $request){
    
        $data=$this->validate($request, [
            'contrat_id' => 'required',
            'patient_id' => 'required',     
            'hospital_id' => 'required',      
            'doctor_id' => 'required', 
            'reference_hopital' => 'nullable', 
            'test_id' => 'required', 
            'prix' => 'required', 
            'remise' => 'required'
        ]);

     

        try {
            DB::transaction(function () use ($data) {
                $contrat = Contrat::find($data['contrat_id']);

                $test = Test::find($data['test_id']);

               
                $category_test = $test->category_test();
              


                $if_test = Details_Contrat::where('contrat_id',$contrat->id)->where('category_test_id',$category_test->id)->get();

                if($if_test->count()==1){
                    //Ce type de test  figure dans le contrat séléctionné
                   
                    $new_price = $data['prix']- $data['remise'];
                    $montant_contrat = ($new_price * $if_test[0]->pourcentage)/100;
                    $montant_patient = ($new_price * (100-$if_test[0]->pourcentage))/100;
                    $montant_total = $montant_contrat + $montant_patient;

                    $test_order = new TestOrder();
                    $test_order->reference_hopital  =$data['reference_hopital'];
                    $test_order->patient_id  =$data['patient_id'];
                    $test_order->doctor_id = $data['doctor_id'];
                    $test_order->hospital_id  =$data['hospital_id'];
                    $test_order->contrat_id  =$data['contrat_id'];
                    $test_order->test_id = $data['test_id'];
                    $test_order->price = $data['prix'];
                    $test_order->remise = $data['remise'];
                    $test_order->montant_contrat = $montant_contrat;
                    $test_order->montant_patient  =$montant_patient;
                    $test_order->montant_total = $montant_total;
                    $test_order->save();

              

                } else {
                    return back()->with('error', "Ce type de test ne figure pas dans le contrat séléctionné ! "); 
                }

              
            });
         
            return back()->with('success', "Demande d'examen enregistré avec succès "); 
          
            } catch(\Throwable $ex){
          return back()->with('error', "Échec de l'enregistrement ! " .$ex->getMessage());
      }
    }



    public function destroy($id){
        TestOrder::find($id)->delete();
        return back()->with('success', "    Un élement a été supprimé ! ");
    }
}
