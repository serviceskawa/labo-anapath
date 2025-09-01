<?php

namespace App\Models;

use App\Models\Prestation;
use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryPrestation extends Model
{
    use HasFactory, BranchScopeTrait;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($categoryPrestation) {
            // verifie s'il a des relations
            if ($categoryPrestation->prestations()->count() > 0) {
                return false;
            }
        });
    }
    /**
     * Get all of the prestations for the CategoryPrestation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prestations()
    {
        return $this->hasMany(Prestation::class);
    }
}
