<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentGatewayFactory;
use InvalidArgumentException;

class PaymentGatewayManager implements PaymentGatewayFactory
{
    protected array $providers = [];
    protected array $instances = [];

    public function __construct()
    {
        $this->registerDefaultProviders();
    }

    /**
     * Register default providers
     */
    protected function registerDefaultProviders(): void
    {
        // Register built-in providers
        $this->registerProvider('payop', PayopPaymentGateway::class);
        $this->registerProvider('paydo', PaydoPaymentGateway::class);
        $this->registerProvider('trongrid', TronGridPaymentGateway::class);
        $this->registerProvider('ngenius', NgeniusPaymentGateway::class);

    }

    /**
     * Create a payment gateway instance
     *
     * @param string $provider
     * @param array $config
     * @return PaymentGateway
     * @throws InvalidArgumentException
     */
    public function create(string $provider, array $config = []): PaymentGateway
    {
        if (!$this->isProviderSupported($provider)) {
            throw new InvalidArgumentException("Payment gateway provider '{$provider}' is not supported.");
        }

        // Use singleton pattern for each provider config combination
        $cacheKey = $provider . '_' . md5(serialize($config));

        if (!isset($this->instances[$cacheKey])) {
            $className = $this->providers[$provider];

            if (!class_exists($className)) {
                throw new InvalidArgumentException("Payment gateway class '{$className}' does not exist.");
            }

            // Merge with default config from configuration
            $defaultConfig = config("payment.gateways.{$provider}", []);
            $mergedConfig = array_merge($defaultConfig, $config);

            $this->instances[$cacheKey] = new $className($mergedConfig);
        }

        return $this->instances[$cacheKey];
    }

    /**
     * Get all available providers
     *
     * @return array
     */
    public function getAvailableProviders(): array
    {
        return array_keys($this->providers);
    }

    /**
     * Check if a provider is supported
     *
     * @param string $provider
     * @return bool
     */
    public function isProviderSupported(string $provider): bool
    {
        return isset($this->providers[$provider]);
    }

    /**
     * Register a new provider
     *
     * @param string $provider
     * @param string $className
     * @return void
     * @throws InvalidArgumentException
     */
    public function registerProvider(string $provider, string $className): void
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class '{$className}' does not exist.");
        }

        if (!is_subclass_of($className, PaymentGateway::class)) {
            throw new InvalidArgumentException("Class '{$className}' must implement PaymentGateway interface.");
        }

        $this->providers[$provider] = $className;
    }

    /**
     * Get provider class name
     *
     * @param string $provider
     * @return string|null
     */
    public function getProviderClassName(string $provider): ?string
    {
        return $this->providers[$provider] ?? null;
    }

    /**
     * Remove a provider
     *
     * @param string $provider
     * @return void
     */
    public function unregisterProvider(string $provider): void
    {
        unset($this->providers[$provider]);

        // Clear related instances
        foreach ($this->instances as $key => $instance) {
            if (str_starts_with($key, $provider . '_')) {
                unset($this->instances[$key]);
            }
        }
    }

    /**
     * Clear all cached instances
     *
     * @return void
     */
    public function clearInstances(): void
    {
        $this->instances = [];
    }

    /**
     * Get cached instance keys
     *
     * @return array
     */
    public function getCachedInstanceKeys(): array
    {
        return array_keys($this->instances);
    }
}
