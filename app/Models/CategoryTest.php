<?php

namespace App\Models;

use App\Models\Contrat;
use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryTest extends Model
{
    use HasFactory, BranchScopeTrait;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($categorieTest) {
            // verifie s'il a des relations
            if ($categorieTest->tests()->count() > 0) {
                return false;
            }
        });
    }

    public function tests(){
        return $this->hasMany(Test::class);
    }

    /**
     * Get all of the contrats for the CategoryTest
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }
}
