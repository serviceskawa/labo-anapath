<?php

namespace App\Models;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
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
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function doctor_interne()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    /**
     * Get the consultation associated with the Appointment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function getWithNewAppointments(){
        return $this->with(['doctor', 'patient', 'doctor_interne']);
    }
}
