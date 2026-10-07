<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * Keeps checkout, the merchant API, provider webhooks and the HTTP
     * scheduler trigger working so payments in progress still complete.
     *
     * `php artisan down` reads this list from this exact class name to build
     * the pre-rendered page, so keep it here rather than in bootstrap/app.php.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*',
        'payment/*',
        'oppwa/*',
        'run-scheduler',
    ];
}
