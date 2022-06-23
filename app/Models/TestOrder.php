<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestOrder extends Model
{


    use HasFactory;
    protected $guarded = [];

    public function getPatient(){
        $data = Patient::find($this->patient_id);
        return $data;
    }

    public function getThisTest(){
        $data = Test::find($this->test_id);
        return $data;
    }

    public function getDoctor(){
        $data = Doctor::find($this->doctor_id);
        return $data;
    }

    public function getHospital(){
        $data = Hospital::find($this->hospital_id);
        return $data;
    }
}
