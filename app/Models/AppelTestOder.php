<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppelTestOder extends Model
{
    use HasFactory, BranchScopeTrait;
    protected $guarded = [];
}
