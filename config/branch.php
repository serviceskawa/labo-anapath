<?php

return [
    'excluded_tables' => [
        'users', 'roles', 'permissions', 'role_user', 'permission_role',
        'branches', 'branch_user', 'settings', 'setting_apps', 'migrations',
        'password_resets', 'personal_access_tokens', 'failed_jobs'
    ],

    'excluded_models' => [
        'App\Models\Patient',
        'App\Models\User',
        'App\Models\Role',
        'App\Models\Permission',
        'App\Models\Branch',
        'App\Models\Setting',
        'App\Models\SettingApp',
    ],

    'auto_filter_enabled' => env('BRANCH_AUTO_FILTER', true),
];
