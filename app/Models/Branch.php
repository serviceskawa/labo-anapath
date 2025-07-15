<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'code',
        'location'
    ];

    // 🔁 Utilisateurs associés via la table pivot
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('is_default')
                    ->withTimestamps();
        // Renvoie tous les utilisateurs associés à cette branche via la pivot
    }

    // 🔁 Utilisateurs pour qui cette branche est la principale (users.branch_id)
    public function primaryUsers()
    {
        return $this->hasMany(User::class, 'branch_id');
        // Renvoie les utilisateurs dont c'est la branche principale
    }
}
