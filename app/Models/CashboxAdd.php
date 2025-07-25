<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashboxAdd extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class,'invoice_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class,'bank_id');
    }
}
