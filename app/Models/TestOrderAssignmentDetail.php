<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestOrderAssignmentDetail extends Model
{
    use HasFactory;
    public $guarded = [];
    
    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'test_order_id');
    }


    public function testOrderAssignment()
    {
        return $this->belongsTo(TestOrderAssignment::class);
    }

    public function testOrder()
    {
        return $this->belongsTo(TestOrder::class, 'test_order_id');
    }

}
