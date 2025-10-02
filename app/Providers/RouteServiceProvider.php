<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/client.php'));

            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            // Example routes (remove in production)
            if (config('app.debug')) {
                Route::middleware('web')
                    ->group(base_path('routes/oppwa-examples.php'));
            }
        });
    }
} 