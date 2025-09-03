<?php

namespace App\Models;

use App\Models\User;
use App\Models\Permission;
use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, BranchScopeTrait;

    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id' )->withPivot(["id"]);
    }

    /**
     * Get the user that owns the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users() {

        return $this->belongsToMany(User::class,'user_roles');

     }

}
