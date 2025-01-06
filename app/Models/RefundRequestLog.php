<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefundRequestLog extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function refund()
    {
        return $this->belongsTo(RefundRequest::class,'refund_request_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
