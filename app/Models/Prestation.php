<?php

namespace App\Models;

use App\Models\CategoryPrestation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the category that owns the Prestation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(CategoryPrestation::class);
    }
}
