<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequest extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function order()
    {
        return $this->belongsTo(TestOrder::class,'test_order_id');
    }
}
