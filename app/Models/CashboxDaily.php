<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashboxDaily extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;

    protected $guarded = [];

    public function cashbox(){
        return $this->belongsTo(Cashbox::class,'cashbox_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
