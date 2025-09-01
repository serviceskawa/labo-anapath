<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategorie extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;

    protected $guarded = [];


    public function expensive()
    {
        return $this->hasMany(Expense::class);
    }
}
