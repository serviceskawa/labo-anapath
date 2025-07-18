<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function report()
    {
        return $this->belongsTo(Report::class, 'created_by');
    }

    public function reports() {

        return $this->belongsToMany(Report::class,'report_tags');

     }
}
