<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getCode($code){
        $report = $this->getCodeByDB($code);
        $signatory1 = "";
        $signatory2 = "";
        $signatory3 = "";

        if($report){
            if($report->signatory1 !=null ){
                $signatory1 = getSignatory1($report->signatory1);
            }
            if($report->signatory2 ){
                $signatory2 = getSignatory1($report->signatory2);
            }
            if($report->signatory3 ){
                $signatory3 = getSignatory1($report->signatory3);
            }
           
            return response()->json(['status'=>200,'data'=>$report,'signatory1'=>$signatory1,'signatory2'=>$signatory2,'signatory3'=>$signatory3]);
        }else{
            return response()->json('error');
        }
    }

    public function getCodeByDB($code){
        
        //recuppérer les deux premiers caractère de la chaine
        $beginCode = substr($code, 0,2);
        //récupérer les caractères à partir de la position 3
        $endCode = substr($code,2);

        $codeGenerate = $beginCode.'-'.$endCode;
        $report = Report::with(['patient'])->whereHas('order',function($query)use($codeGenerate){
            $query->where('code','like', '%'.$codeGenerate);
        })->first();
        return $report;
    }

    public function getCodeDB($code){
        
         //recuppérer les deux premiers caractère de la chaine
         $beginCode = substr($code, 0,2);
         //récupérer les caractères à partir de la position 3
         $endCode = substr($code,2);

        $codeGenerate = $beginCode.'-'.$endCode;
        $report = Report::with(['patient'])->whereHas('order',function($query)use($codeGenerate){
            $query->where('code','like', '%'.$codeGenerate);
        })->first();
        if($report){
            return response()->json($report->order->code);
        }else{
            return response()->json(false);
        }
    }
}
