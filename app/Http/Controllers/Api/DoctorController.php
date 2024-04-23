<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\TestOrder;
use App\Models\TestOrderAssignmentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{

    public function get_doctors() {
        $doctors = Doctor::get();

        return response()->json(['results' => $doctors]);
    }

    public function AllTestOrders()
    {
        $results = TestOrder::with('report','patient','details','type','contrat', 'doctor' , 'hospital' , 'doctorExamen' , 'attribuateToDoctor')->latest()->paginate(15);

        return response()->json([$results]);
    }
    
    public function searchAffectation($query){
      
        $results = TestOrder::where('code', 'like', '%' . $query . '%')->get();

        return response()->json(['results' => $results]);
    }


    public function searchAffectationByDoctor($doctorId){
        // $results = TestOrder::where('attribuate_doctor_id',$doctorId)->get();

        $results = TestOrderAssignmentDetail::with(['testOrderAssignment','testOrder.patient', 'testOrder.contrat', 'testOrder.type', 'testOrder.details', 'testOrder.report'])
        ->whereHas('testOrder.type', function($query) use ($doctorId){
            $query->whereIn('slug', ['immuno-exterme', 'immuno-interne', 'histologie', 'cytologie', 'biopsie', 'pièce-opératoire'])
                ->whereNull('deleted_at');
        })->whereHas('testOrderAssignment', function($query) use ($doctorId) {
            $query->where('user_id', $doctorId); // Modifier la condition pour status égal à 0
        })->orderBy('created_at', 'desc')->get();

        return response()->json(['results' => $results]);
    }



    public function getOldTestOrders($doctorId)
    {
        // $tenDaysAgo = Carbon::now()->subDays(10);

        // $oldTestOrders = TestOrder::where('assignment_date', '<', $tenDaysAgo)
        // ->where('attribuate_doctor_id',$doctorId)
        // ->get();



        $oldTestOrders = TestOrderAssignmentDetail::with(['testOrderAssignment','testOrder.patient', 'testOrder.contrat', 'testOrder.type', 'testOrder.details', 'testOrder.report'])
        ->whereHas('testOrder.type', function($query) use ($doctorId){
            $query->whereIn('slug', ['immuno-exterme', 'immuno-interne', 'histologie', 'cytologie', 'biopsie', 'pièce-opératoire'])
                ->whereNull('deleted_at');
        })->whereHas('testOrder.report', function($query) use ($doctorId) {
            $query->where('created_at', '<=', Carbon::now()->subDays(21)); // Modifier la condition pour status égal à 0
        })->whereHas('testOrderAssignment', function($query) use ($doctorId) {
            $query->where('user_id', $doctorId); // Modifier la condition pour status égal à 0
        })->orderBy('created_at', 'desc')->get();
        

        return response()->json(['old_articles' => $oldTestOrders]);
    }
    
    public function updateInformOrDeliveryPatientStatus(Request $request) {
        $report = Report::where('id',$request->id)->first();

        if($request->action == "information"){
            $report->update([
                'is_called' => 1,
                'call_date' => now()
            ]);
        }else{

            if($report->is_called == 0) {
                return response()->json([
                    'errors' => [
                        'message' => 'Patient non informé.'
                    ]
                ], 422);
            }
            $report->update([
                'is_delivered' => 1,
                'delivery_date' => now()
            ]);
        }
        return response()->json(["message" => "Operation réussie avec succès!"], 200);
    }
}
