<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Schema;

class GlobalBranchServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Macros pour Query Builder
        $this->registerQueryBuilderMacros();

        // Override du Query Builder
        $this->overrideQueryBuilder();
    }

    private function registerQueryBuilderMacros()
    {
        // Macro pour ajouter automatiquement le scope de branche
        QueryBuilder::macro('addBranchScope', function () {
            if (property_exists($this, 'branchScopeApplied') && $this->branchScopeApplied === true) {
                return $this;
            }

            $table = $this->from;

            $excludedTables = [
                'users', 'roles', 'permissions', 'role_user', 'permission_role',
                'branches', 'branch_user', 'settings', 'setting_apps', 'migrations',
                'password_resets', 'personal_access_tokens', 'failed_jobs'
            ];

            if (in_array($table, $excludedTables)) {
                return $this;
            }

            try {
                if (!Schema::hasColumn($table, 'branch_id')) {
                    return $this;
                }
            } catch (\Exception $e) {
                return $this;
            }

            if (Auth::check() && Session::has('selected_branch_id')) {
                $branchId = Session::get('selected_branch_id');
                if ($branchId) {
                    $this->where($table . '.branch_id', $branchId);
                    $this->branchScopeApplied = true;
                }
            }

            return $this;
        });

        QueryBuilder::macro('withoutBranchScope', function () {
            $this->branchScopeApplied = false;
            return $this;
        });
    }

    private function overrideQueryBuilder()
    {
        QueryBuilder::macro('get', function ($columns = ['*']) {
            $this->addBranchScope();
            $grammar = $this->getGrammar();
            $processor = $this->getProcessor();
            $results = $this->connection->select(
                $grammar->compileSelect($this), $this->getBindings()
            );
            return $processor->processSelect($this, $results);
        });

        QueryBuilder::macro('first', function ($columns = ['*']) {
            $result = $this->addBranchScope()->take(1)->get($columns);
            return $result->isEmpty() ? null : $result->first();
        });

        QueryBuilder::macro('count', function ($columns = '*') {
            $this->addBranchScope();
            return (int) $this->aggregate(__FUNCTION__, is_array($columns) ? $columns : [$columns]);
        });

        QueryBuilder::macro('exists', function () {
            $this->addBranchScope();
            $results = $this->connection->select(
                $this->getGrammar()->compileExists($this),
                $this->getBindings()
            );
            if (isset($results[0])) {
                $results = (array) $results[0];
                return (bool) $results['exists'];
            }
            return false;
        });

        QueryBuilder::macro('update', function (array $values) {
            $this->addBranchScope();

            $table = $this->from;
            $excludedTables = [
                'users', 'roles', 'permissions', 'role_user', 'permission_role',
                'branches', 'branch_user', 'settings', 'setting_apps', 'migrations',
                'password_resets', 'personal_access_tokens', 'failed_jobs'
            ];

            if (!in_array($table, $excludedTables) &&
                !isset($values['branch_id']) &&
                Auth::check() &&
                Session::has('selected_branch_id')) {

                try {
                    if (Schema::hasColumn($table, 'branch_id')) {
                        $values['branch_id'] = Session::get('selected_branch_id');
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            $sql = $this->getGrammar()->compileUpdate($this, $values);
            return $this->connection->update($sql, $this->getBindings());
        });

        QueryBuilder::macro('delete', function ($id = null) {
            if (isset($id)) {
                $this->where($this->from.'.id', '=', $id);
            }

            $this->addBranchScope();
            $sql = $this->getGrammar()->compileDelete($this);
            return $this->connection->delete($sql, $this->getBindings());
        });

        QueryBuilder::macro('insert', function ($values) {
            if (empty($values)) {
                return true;
            }

            if (!is_array(reset($values))) {
                $values = [$values];
            }

            $table = $this->from;
            $excludedTables = [
                'users', 'roles', 'permissions', 'role_user', 'permission_role',
                'branches', 'branch_user', 'settings', 'setting_apps', 'migrations',
                'password_resets', 'personal_access_tokens', 'failed_jobs'
            ];

            if (!in_array($table, $excludedTables) &&
                Auth::check() &&
                Session::has('selected_branch_id')) {

                try {
                    if (Schema::hasColumn($table, 'branch_id')) {
                        $branchId = Session::get('selected_branch_id');

                        foreach ($values as &$row) {
                            if (!isset($row['branch_id'])) {
                                $row['branch_id'] = $branchId;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            $sql = $this->getGrammar()->compileInsert($this, $values);
            $bindings = $this->cleanBindings(array_flatten($values, 1));

            return $this->connection->insert($sql, $bindings);
        });

        QueryBuilder::macro('insertGetId', function ($values, $sequence = null) {
            $table = $this->from;
            $excludedTables = [
                'users', 'roles', 'permissions', 'role_user', 'permission_role',
                'branches', 'branch_user', 'settings', 'setting_apps', 'migrations',
                'password_resets', 'personal_access_tokens', 'failed_jobs'
            ];

            if (!in_array($table, $excludedTables) &&
                Auth::check() &&
                Session::has('selected_branch_id') &&
                is_array($values) &&
                !isset($values['branch_id'])) {

                try {
                    if (Schema::hasColumn($table, 'branch_id')) {
                        $values['branch_id'] = Session::get('selected_branch_id');
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            $sql = $this->getGrammar()->compileInsertGetId($this, $values, $sequence);
            $bindings = $this->cleanBindings(array_flatten($values, 1));

            return $this->connection->insertGetId($sql, $bindings, $sequence);
        });
    }
}
