<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/examens/categories',
        '/examens/categories/update',
        '/examens/index',
        '/examens/update',
        '/hopitals/index',
        '/hopitals/update',
        '/doctors/index',
        '/doctors/update',
        '/patients/index',
        '/patients/update',
        '/contrats/index',
        '/contrats/update',
        '/contrats/details/store',
        '/contrats_details/update',
        '/test_order/store',
        '/test_order/updatetesttotal',
        '/test_order/updatetest',
    ];
}
