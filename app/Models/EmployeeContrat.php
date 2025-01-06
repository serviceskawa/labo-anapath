<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeContrat extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
    
    public function employee_payroll()
    {
        return $this->hasMany(EmployeePayroll::class);
    }

}