<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentationCategorie extends Model
{
    use HasFactory, SoftDeletes, BranchScopeTrait;
    protected $guarded = [];

    public function docs()
    {
        return $this->hasMany(Doc::class);
    }
}
