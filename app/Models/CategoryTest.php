<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($contrat) {
            // verifie s'il a des relations
            if ($contrat->tests()->count() > 0) {
                return false;
            }
        });
    }

    public function tests(){
        return $this->hasMany(Test::class);
    }
}
