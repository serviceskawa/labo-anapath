<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Guid\Guid;

class TestOrderAssignment extends Model
{
    use HasFactory, BranchScopeTrait;
    public $guarded = [];

    public function testOrderAssignmentDetails()
    {
        return $this->hasMany(TestOrderAssignmentDetail::class, 'test_order_assignment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TestOrderAssignmentDetail::class, 'test_order_assignment_id');
    }

    public function assignmentDetails()
    {
        return $this->hasMany(TestOrderAssignmentDetail::class, 'test_order_assignment_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id'); // Relation avec le modèle User
    }


    public function report()
    {
        return $this->hasOne(Report::class, 'test_order_id', 'test_order_id');
    }
}
