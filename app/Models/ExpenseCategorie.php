<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseCategorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];


    public function expensive()
    {
        return $this->hasMany(Expense::class);
    }
}