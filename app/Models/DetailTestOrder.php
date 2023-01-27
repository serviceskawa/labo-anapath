<?php

namespace App\Models;

use App\Models\TestOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetailTestOrder extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function test_order()
    {
        $data = TestOrder::where('id', $this->test_order_id)->first();
        return $data;
    }


    public function test()
    {
        $data = Test::where('id', $this->test_id)->first();
        return $data;
    }

    /**
     * Get the order that owns the DetailTestOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'test_order_id');
    }
}
