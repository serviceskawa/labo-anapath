<?php

namespace App\Http\Controllers;

use App\Models\Details_Contrat;
use App\Models\CategoryTest;
use Illuminate\Http\Request;

class DetailsContratController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }
    
    public function getremise($contratId, $categoryTestId){
        //dd("frh");
        $data = Details_Contrat::where(['contrat_id' => $contratId, 'category_test_id' => $categoryTestId])->first();
        if($data == null){
            return 0;
        }else{
            return $data->pourcentage;
        }

    }
}
