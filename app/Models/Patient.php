<?php

namespace App\Models;

use App\Models\TestOrder;
use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Patient extends Model
{
    use HasFactory, BranchScopeTrait;
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($contrat) {
            // verifie s'il a des relations
            if ($contrat->orders()->count() > 0) {
                return false;
            }
        });
    }

    /**
     * Get all of the orders for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(TestOrder::class);
    }
}
