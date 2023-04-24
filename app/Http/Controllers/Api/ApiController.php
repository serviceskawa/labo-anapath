<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCode($code){
        $report = Report::with(['patient'])->where('code','like', $code)->first();

        if($report){
            if($report->signatory1 !=null ){
                $signatory1 = getSignatory1($report->signatory1);
            }else{
                $signatory1="";
            }
            if($report->signatory2 ){
                $signatory2 = getSignatory1($report->signatory2);
            }else{
                $signatory2 = "";
            }
            if($report->signatory3 ){
                $signatory3 = getSignatory1($report->signatory3);
            }else{
                $signatory3 = "";
            }
            $reports = Report::all();
            //dd($report);
            return response()->json(['data'=>$report,'signatory1'=>$signatory1,'signatory2'=>$signatory2,'signatory3'=>$signatory3]);
        }else{
            return response()->json('error');
        }
    }
}
