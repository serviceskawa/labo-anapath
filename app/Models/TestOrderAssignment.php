<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Guid\Guid;

class TestOrderAssignment extends Model
{
    use HasFactory;
    public $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function details()
    {
        return $this->hasMany(TestOrderAssignmentDetail::class,'test_order_assignment_id');
    }
}
