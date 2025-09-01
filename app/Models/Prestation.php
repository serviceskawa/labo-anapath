<?php

namespace App\Models;

use App\Models\Consultation;
use App\Models\CategoryPrestation;
use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestation extends Model
{
    use HasFactory, BranchScopeTrait;

    protected $guarded = [];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($prestation) {
            // verifie s'il a des relations
            if ($prestation->consultations()->count() > 0) {
                return false;
            }
        });
    }

    /**
     * Get the category that owns the Prestation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(CategoryPrestation::class, 'category_prestation_id');
    }

    /**
     * Get all of the consultations for the Prestation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'prestation_id');
    }
}
