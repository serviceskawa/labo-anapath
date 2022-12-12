<?php

namespace App\Models;

//use App\Models\Contrat;
use App\Models\Contrat;
use App\Models\TestOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contrat extends Model
{
    protected $guarded = [];

    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($contrat) {
            // verifie s'il a des relations
            if ($contrat->detail()->count() > 0 || $contrat->orders()->count() > 0) {
                return false;
            }
        });
    }
    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get all of the orders for the Contrat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(TestOrder::class);
    }

    /**
     * Get the detail associated with the Contrat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function detail()
    {
        return $this->hasOne(Details_Contrat::class);
    }
}
