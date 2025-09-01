<?php

namespace App\Models;

use App\Traits\BranchScopeTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeConsultation extends Model
{
    use HasFactory, BranchScopeTrait;

    protected $guarded = [];

    /**
     * The type_files that belong to the TypeConsultation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function type_files()
    {
        return $this->belongsToMany(TypeConsultationFile::class, 'type_consultation_type_consultation_file', 'type_id', 'type_file_id');
    }
}
