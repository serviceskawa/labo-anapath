<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Schema;

class GlobalBranchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $excludedTables = [
            'users', 'roles', 'permissions', 'role_user', 'permission_role',
            'branches', 'branch_user', 'settings', 'setting_apps', 'migrations',
            'password_resets', 'personal_access_tokens', 'failed_jobs'
        ];

        // Helper qui applique automatiquement branch_id
        QueryBuilder::macro('applyBranchFilter', function () use ($excludedTables) {
            $table = $this->from;

            if (
                !in_array($table, $excludedTables) &&
                auth()->check() &&
                session()->has('selected_branch_id') &&
                !isset($this->branchFilterApplied)
            ) {
                if (Schema::hasColumn($table, 'branch_id')) {
                    $this->where($table . '.branch_id', session('selected_branch_id'));
                    $this->branchFilterApplied = true;
                }
            }

            return $this;
        });

        // Intercepter les méthodes principales
        foreach (['get', 'first', 'count', 'exists', 'toSql'] as $method) {
            QueryBuilder::macro($method, function (...$args) use ($method) {
                $this->applyBranchFilter();
                return parent::$method(...$args);
            });
        }

        // Permet d’ignorer le filtre
        QueryBuilder::macro('withoutBranchFilter', function () {
            $this->branchFilterApplied = true;
            return $this;
        });
    }
}
