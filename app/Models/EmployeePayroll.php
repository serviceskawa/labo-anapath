<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeePayroll extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;
    protected $guarded = [];

    // public function employee_contrat()
    // {
    //     return $this->belongsTo(EmployeeContrat::class);
    // }

    public function employee_contrat()
    {
        return $this->belongsTo(EmployeeContrat::class,'employee_contrat_id');
    }
}
