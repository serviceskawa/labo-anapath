<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;

class BranchObserver
{
    public function creating(Model $model)
    {
        $this->assignBranchId($model);
    }

    public function saving(Model $model)
    {
        if (!$model->exists) {
            $this->assignBranchId($model);
        }
    }

    public function updating(Model $model)
    {
        if ($model->isDirty('branch_id') && $model->getOriginal('branch_id')) {
            $model->branch_id = $model->getOriginal('branch_id');
        }
    }

    private function assignBranchId(Model $model)
    {
        if (!$this->shouldHaveBranchId($model)) {
            return;
        }

        if (!$model->branch_id) {
            $branchId = $this->getCurrentBranchId();
            if ($branchId) {
                $model->branch_id = $branchId;
            }
        }
    }

    private function shouldHaveBranchId(Model $model)
    {
        $excludedModels = [
            'App\Models\User',
            'App\Models\Role',
            'App\Models\Permission',
            'App\Models\Branch',
            'App\Models\Setting',
            'App\Models\SettingApp',
        ];

        $modelClass = get_class($model);
        if (in_array($modelClass, $excludedModels)) {
            return false;
        }

        try {
            $table = $model->getTable();
            return Schema::hasColumn($table, 'branch_id');
        } catch (\Exception $e) {
            return false;
        }
    }

    private function getCurrentBranchId()
    {
        if (Auth::check() && Session::has('selected_branch_id')) {
            return Session::get('selected_branch_id');
        }
        return null;
    }
}
