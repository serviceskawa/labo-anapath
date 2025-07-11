<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeTimeoff extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    public function employee()
    {
        return $this->belongsTo(Employee::class,'employee_id');
    }
}