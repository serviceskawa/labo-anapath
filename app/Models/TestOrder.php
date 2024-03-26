<?php

namespace App\Models;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Contrat;
use App\Models\Invoice;
use App\Models\Hospital;
use App\Models\TypeOrder;
use App\Models\DetailTestOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestOrder extends Model
{

    use HasFactory, SoftDeletes;

    protected $guarded = [];    
    

    public function getPatient()
    {
        $data = Patient::find($this->patient_id);
        return $data;
    }

    public function getThisTest()
    {
        $data = Test::find($this->test_id);
        return $data;
    }

    public function getDoctor()
    {
        $data = Doctor::find($this->doctor_id);
        return $data;
    }

    public function getHospital()
    {
        $data = Hospital::find($this->hospital_id);
        return $data;
    }

    public function getContrat()
    {
        $data = Contrat::find($this->contrat_id);
        return $data;
    }

    public function getReport($id)
    {
        $data = Report::where('test_order_id', $id)->first();
        if (is_null($data)) {
            return 2; // pour différencier les valeurs des status. 0 pour en attente, 1 valider et 2 pour examin qui n'a pas été enregistré
        } else {
            return $data->status;
        }
    }
    public function getReportId($id)
    {
        $data = Report::where('test_order_id', $id)->first();
        if (is_null($data)) {
            return null;
        } else {
            return $data->id;
        }
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
        return $this->belongsTo(Doctor::class, 'doctor_id');
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

    /**
     * Get all of the details for the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(DetailTestOrder::class, 'test_order_id');
    }

    public function testOrderMacro()
    {
        return $this->hasMany(test_pathology_macro::class, 'id_test_pathology_order');
    }

    /**
     * Get the Hospital that owns the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }


     /**
     * Get the Hospital that owns the Doctor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctorExamen()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Get the contrat that owns the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    /**
     * Get the type that owns the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(TypeOrder::class, 'type_order_id');
    }

    /**
     * Get the invoice associated with the TestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function invoice()
    {
        return $this->hasOne(Invoice::class,'test_order_id');
    }


    public function macro()
    {
        return $this->hasOne(test_pathology_macro::class,'test_order_id');
    }

    public function attribuateToDoctor()
    {
        return $this->belongsTo(User::class, 'attribuate_doctor_id');
    }
}
