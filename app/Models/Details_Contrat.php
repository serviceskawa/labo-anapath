<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Details_Contrat extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function categorytest(){
        $data = CategoryTest::where('id',$this->category_test_id)->first();
        return $data;
    }


}
