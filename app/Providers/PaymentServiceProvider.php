<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayFactory;
use App\Services\PaymentGateway\PaymentGatewayManager;
use App\Services\PaymentService;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the payment gateway factory
        $this->app->singleton(PaymentGatewayFactory::class, function ($app) {
            return new PaymentGatewayManager();
        });

        // Register the main payment service
        $this->app->singleton(PaymentService::class, function ($app) {
            return new PaymentService($app->make(PaymentGatewayFactory::class));
        });

        // Register aliases for easier access
        $this->app->alias(PaymentService::class, 'payment');
        $this->app->alias(PaymentGatewayFactory::class, 'payment.factory');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../../config/payment.php' => config_path('payment.php'),
        ], 'payment-config');

        // Load routes if needed
        // $this->loadRoutesFrom(__DIR__ . '/../../routes/payment.php');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            PaymentGatewayFactory::class,
            PaymentService::class,
            'payment',
            'payment.factory',
        ];
    }
} 