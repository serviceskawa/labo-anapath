<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UnitMeasurement extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;
    protected $guarded = [];

    public function articles()
    {
        return $this->hasMany(Article::class,'unit_measurement_id');
    }
}
