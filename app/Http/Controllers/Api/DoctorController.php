<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TestOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoctorController extends Controller
{

    public function AllTestOrders()
    {
        $results = TestOrder::latest()->get();

        return response()->json(['results' => $results]);
    }
    public function searchAffectation($query){
      
        $results = TestOrder::where('code', 'like', '%' . $query . '%')->get();

        return response()->json(['results' => $results]);
    }


    public function searchAffectationByDoctor($doctorId){

        // $query = $query = $request->input('query');
      
        $results = TestOrder::where('attribuate_doctor_id',$doctorId)->get();

        return response()->json(['results' => $results]);
    }



    public function getOldTestOrders($doctorId)
    {
        $tenDaysAgo = Carbon::now()->subDays(10);

        $oldTestOrders = TestOrder::where('assignment_date', '<', $tenDaysAgo)
        ->where('attribuate_doctor_id',$doctorId)
        ->get();

        return response()->json(['old_articles' => $oldTestOrders]);
    }
}
