<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function roles() {

        return $this->belongsToMany(Role::class,'role_permissions');
            
     }
    public function users() {

        return $this->belongsToMany(User::class,'users_permissions');
            
     }

      /**
     * Get the operation that owns the Operation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }
}
