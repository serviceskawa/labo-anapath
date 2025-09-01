<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;
    protected $guarded = [];
    public function employee_contrat()
    {
        return $this->hasMany(EmployeeContrat::class,'employee_id');
    }

    public function employee_document()
    {
        return $this->hasMany(EmployeeDocument::class,'employee_id');
    }

    public function employee_timeoff()
    {
        return $this->hasMany(EmployeeTimeoff::class,'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function fullname()
    {
        return $this->first_name .' '.$this->last_name ;
    }
}
