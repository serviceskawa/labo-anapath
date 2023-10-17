<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doc extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];


    public function document_categorie()
    {
        return $this->belongsTo(DocumentationCategorie::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
