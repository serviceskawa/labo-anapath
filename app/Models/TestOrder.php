<?php

namespace App\Models;

use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestOrder extends Model
{

    use HasFactory , SoftDeletes;
    
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

    public function getContrat(){
        $data = Contrat::find($this->contrat_id);
        return $data;
    }

    /**
     * Get the patient that owns the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class,);
    }

    /**
     * Get the report associated with the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function report()
    {
        return $this->hasOne(Report::class);
    }
}
