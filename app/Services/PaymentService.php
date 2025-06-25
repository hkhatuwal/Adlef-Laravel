<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentGatewayFactory;
use App\Contracts\PaymentResponse;
use App\Services\PaymentGateway\PaymentGatewayManager;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class PaymentService
{
    protected PaymentGatewayFactory $gatewayFactory;
    protected array $defaultConfig;

    public function __construct(PaymentGatewayFactory $gatewayFactory = null)
    {
        $this->gatewayFactory = $gatewayFactory ?? new PaymentGatewayManager();
        $this->defaultConfig = config('payment.default', []);
    }

    /**
     * Process a payment using the specified provider
     *
     * @param string $provider
     * @param array $paymentData
     * @param array $config
     * @return PaymentResponse
     */
    public function processPayment(string $provider, array $paymentData, array $config = []): PaymentResponse
    {
        try {
            $gateway = $this->createGateway($provider, $config);
            
            // Validate required payment data
            $this->validatePaymentData($paymentData);
            
            Log::info("Processing payment", [
                'provider' => $provider,
                'amount' => $paymentData['amount'] ?? null,
                'currency' => $paymentData['currency'] ?? null
            ]);

            return $gateway->processPayment($paymentData);
            
        } catch (\Exception $e) {
            Log::error("Payment processing failed", [
                'provider' => $provider,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return new PaymentResponse(
                success: false,
                message: $e->getMessage(),
                errorCode: 'PAYMENT_SERVICE_ERROR'
            );
        }
    }

    /**
     * Refund a payment using the specified provider
     *
     * @param string $provider
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @param array $config
     * @return PaymentResponse
     */
    public function refundPayment(
        string $provider, 
        string $transactionId, 
        float $amount, 
        array $options = [], 
        array $config = []
    ): PaymentResponse {
        try {
            $gateway = $this->createGateway($provider, $config);
            
            if (!$gateway->supportsFeature('refunds')) {
                throw new InvalidArgumentException("Provider '{$provider}' does not support refunds");
            }

            Log::info("Processing refund", [
                'provider' => $provider,
                'transaction_id' => $transactionId,
                'amount' => $amount
            ]);

            return $gateway->refundPayment($transactionId, $amount, $options);
            
        } catch (\Exception $e) {
            Log::error("Refund processing failed", [
                'provider' => $provider,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);

            return new PaymentResponse(
                success: false,
                message: $e->getMessage(),
                errorCode: 'REFUND_SERVICE_ERROR'
            );
        }
    }

    /**
     * Get payment status using the specified provider
     *
     * @param string $provider
     * @param string $transactionId
     * @param array $config
     * @return PaymentResponse
     */
    public function getPaymentStatus(string $provider, string $transactionId, array $config = []): PaymentResponse
    {
        try {
            $gateway = $this->createGateway($provider, $config);
            return $gateway->getPaymentStatus($transactionId);
            
        } catch (\Exception $e) {
            Log::error("Payment status retrieval failed", [
                'provider' => $provider,
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);

            return new PaymentResponse(
                success: false,
                message: $e->getMessage(),
                errorCode: 'STATUS_SERVICE_ERROR'
            );
        }
    }

    /**
     * Verify webhook signature
     *
     * @param string $provider
     * @param string $payload
     * @param string $signature
     * @param array $config
     * @return bool
     */
    public function verifyWebhookSignature(
        string $provider, 
        string $payload, 
        string $signature, 
        array $config = []
    ): bool {
        try {
            $gateway = $this->createGateway($provider, $config);
            
            if (!$gateway->supportsFeature('webhooks')) {
                Log::warning("Provider '{$provider}' does not support webhooks");
                return false;
            }

            $secret = $config['webhook_secret'] ?? config("payment.gateways.{$provider}.webhook_secret");
            
            if (!$secret) {
                Log::error("Webhook secret not configured for provider '{$provider}'");
                return false;
            }

            return $gateway->verifyWebhookSignature($payload, $signature, $secret);
            
        } catch (\Exception $e) {
            Log::error("Webhook verification failed", [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get available payment providers
     *
     * @return array
     */
    public function getAvailableProviders(): array
    {
        return $this->gatewayFactory->getAvailableProviders();
    }

    /**
     * Get supported payment methods for a provider
     *
     * @param string $provider
     * @param array $config
     * @return array
     */
    public function getSupportedPaymentMethods(string $provider, array $config = []): array
    {
        try {
            $gateway = $this->createGateway($provider, $config);
            return $gateway->getSupportedPaymentMethods();
        } catch (\Exception $e) {
            Log::error("Failed to get supported payment methods", [
                'provider' => $provider,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Check if a provider supports a specific feature
     *
     * @param string $provider
     * @param string $feature
     * @param array $config
     * @return bool
     */
    public function providerSupportsFeature(string $provider, string $feature, array $config = []): bool
    {
        try {
            $gateway = $this->createGateway($provider, $config);
            return $gateway->supportsFeature($feature);
        } catch (\Exception $e) {
            Log::error("Failed to check provider feature support", [
                'provider' => $provider,
                'feature' => $feature,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Register a new payment provider
     *
     * @param string $provider
     * @param string $className
     * @return void
     */
    public function registerProvider(string $provider, string $className): void
    {
        $this->gatewayFactory->registerProvider($provider, $className);
    }

    /**
     * Create a payment gateway instance
     *
     * @param string $provider
     * @param array $config
     * @return PaymentGateway
     */
    protected function createGateway(string $provider, array $config = []): PaymentGateway
    {
        return $this->gatewayFactory->create($provider, $config);
    }

    /**
     * Validate payment data
     *
     * @param array $paymentData
     * @throws InvalidArgumentException
     */
    protected function validatePaymentData(array $paymentData): void
    {
        $requiredFields = ['amount'];
        
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field]) || empty($paymentData[$field])) {
                throw new InvalidArgumentException("Required payment field '{$field}' is missing or empty");
            }
        }

        if (!is_numeric($paymentData['amount']) || $paymentData['amount'] <= 0) {
            throw new InvalidArgumentException("Payment amount must be a positive number");
        }
    }

    /**
     * Get default provider from configuration
     *
     * @return string
     */
    public function getDefaultProvider(): string
    {
        return $this->defaultConfig['provider'] ?? 'stripe';
    }

    /**
     * Process payment using default provider
     *
     * @param array $paymentData
     * @return PaymentResponse
     */
    public function processPaymentWithDefault(array $paymentData): PaymentResponse
    {
        return $this->processPayment($this->getDefaultProvider(), $paymentData);
    }
} 