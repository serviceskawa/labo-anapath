<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTestOrder extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function test_order(){
        $data = TestOrder::where('id',$this->test_order_id)->first();
        return $data;
    }


    public function test(){
        $data = Test::where('id',$this->test_id)->first();
        return $data;
    }

}
