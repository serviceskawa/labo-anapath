<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;

    protected $guarded = [];

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function unit()
    {
        return $this->belongsTo(UnitMeasurement::class,'unit_measurement_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
