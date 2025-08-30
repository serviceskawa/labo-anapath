<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BranchScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (auth()->check() && session('selected_branch_id')) {
            $builder->where('branch_id', session('selected_branch_id'));
        }
    }

    // public function apply(Builder $builder, Model $model)
    // {
    //     if (auth()->check() && session()->has('selected_branch_id')) {
    //         $builder->where($model->getTable() . '.branch_id', session('selected_branch_id'));
    //     }
    // }
}
