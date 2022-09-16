<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany('App\Models\Role', 'role_permissions', 'permission_id', 'role_id')->withPivot(["id","operation"]);
    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_permissions', 'role_id', 'permission_id')->withPivot(["id","operation"]);
    }

}
