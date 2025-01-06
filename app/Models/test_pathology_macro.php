<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test_pathology_macro extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }
    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'id_test_pathology_order');
    }

    public function testOrder()
    {
        return $this->belongsTo(TestOrder::class, 'id_test_pathology_order');
    }

}
