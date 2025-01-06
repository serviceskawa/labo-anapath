<?php

namespace App\Http\Controllers;

use App\Models\Details_Contrat;
use App\Models\CategoryTest;
use Illuminate\Http\Request;

class DetailsContratController extends Controller
{
    protected $detailContrat;
    public function __construct(Details_Contrat $detailContrat)
    {
        $this->middleware('auth');
        $this->detailContrat = $detailContrat;
    }
    
    public function getremise($contratId, $categoryTestId){

        $data = $this->detailContrat->where(['contrat_id' => $contratId, 'category_test_id' => $categoryTestId])->first();
        if($data == null){
            return 0;
        }else{
            return $data->pourcentage;
        }

    }
}
