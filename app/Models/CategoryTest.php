<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tests(){
        return $this->hasMany(Test::class);
    }
}
