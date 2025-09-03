<?php

namespace App\Traits;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait BranchScopeTrait
{
    protected static function bootBranchScopeTrait()
    {
        // static::addGlobalScope(new BranchScope);
        static::addGlobalScope('branch', function ($builder) {
            if (Auth::check() && session('selected_branch_id')) {
                $table = $builder->getModel()->getTable();
                // Utiliser le nom complet de la colonne avec la table
                $builder->where("{$table}.branch_id", session('selected_branch_id'));
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && session()->has('selected_branch_id')) {
                $model->branch_id = session('selected_branch_id');
            }
        });
    }
}
