<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait BranchScopeTrait
{
    protected static function bootBranchScopeTrait()
    {
        static::addGlobalScope('branch', function (Builder $builder) {
            if (Auth::check() && Session::has('selected_branch_id')) {
                $builder->where('branch_id', Session::get('selected_branch_id'));
            }
        });
    }

    public function scopeWithoutBranchScope($query)
    {
        return $query->withoutGlobalScope('branch');
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->withoutGlobalScope('branch')->where('branch_id', $branchId);
    }

    public function scopeAllBranches($query)
    {
        return $query->withoutGlobalScope('branch');
    }
}
