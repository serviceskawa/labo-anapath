<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RolePermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'role_permissions';

    public function permission()
    {
        return $this->belongsTo('App\Models\Permission');
    }
}
