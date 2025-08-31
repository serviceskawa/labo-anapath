<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashbox extends Model
{
    use HasFactory, BranchScopeTrait;
    protected $guarded = [];

    public function daily(){
        return $this->hasMany(CashboxDaily::class);
    }
}
