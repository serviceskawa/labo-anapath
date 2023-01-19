<?php

namespace App\Models;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\TypeConsultation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the doctor that owns the Appointment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function type()
    {
        return $this->belongsTo(TypeConsultation::class, 'type_consultation_id');
    }
}
