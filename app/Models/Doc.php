<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doc extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;
    protected $guarded = [];


    public function document_categorie()
    {
        return $this->belongsTo(DocumentationCategorie::class,'documentation_categorie_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DocVersion::class, 'doc_id');
    }
}
