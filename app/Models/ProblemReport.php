<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProblemReport extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(TestOrder::class,'test_order_id');
    }

    public function categorie()
    {
        return $this->belongsTo(ProblemCategory::class, 'errorCategory_id');
    }
}
