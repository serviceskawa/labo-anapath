<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory, BranchScopeTrait;
    protected $fillable = [
        'name',
        'account_number',
        'description'
    ];
}
