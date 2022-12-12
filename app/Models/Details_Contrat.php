<?php

namespace App\Models;

use App\Models\Contrat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Details_Contrat extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function categorytest(){
        $data = CategoryTest::where('id',$this->category_test_id)->first();
        return $data;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($detail) {
            // verifie s'il a des relations
            if ($detail->contrat()->count() > 0 ) {
                return false;
            }
        });
    }
    /**
     * Get the contrat that owns the Details_Contrat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contrat()
    {
        return $this->belongsTo(Contrat::class, 'contrat_id');
    }

}
