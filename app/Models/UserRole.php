<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserRole extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'user_roles';

}
