<?php

namespace App\Contracts;

interface PaymentGatewayFactory
{
    /**
     * Create a payment gateway instance
     *
     * @param string $provider
     * @param array $config
     * @return PaymentGateway
     * @throws \InvalidArgumentException
     */
    public function create(string $provider, array $config = []): PaymentGateway;

    /**
     * Get all available providers
     *
     * @return array
     */
    public function getAvailableProviders(): array;

    /**
     * Check if a provider is supported
     *
     * @param string $provider
     * @return bool
     */
    public function isProviderSupported(string $provider): bool;

    /**
     * Register a new provider
     *
     * @param string $provider
     * @param string $className
     * @return void
     */
    public function registerProvider(string $provider, string $className): void;
} 