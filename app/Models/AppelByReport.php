<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppelByReport extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function appel_event()
    {
        return $this->belongsTo(AppelTestOder::class, 'appel_id');
    }
    
}
