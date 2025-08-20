<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentGateway;
use App\Contracts\PaymentResponse;
use App\Contracts\WebhookData;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

abstract class AbstractPaymentGateway implements PaymentGateway
{
    protected array $config;
    protected string $baseUrl;
    protected array $headers;
    protected array $supportedFeatures = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->baseUrl = $config['base_url'] ?? '';
        $this->headers = $config['headers'] ?? [];
        $this->validateConfig();
    }

    /**
     * Validate the configuration
     *
     * @throws \InvalidArgumentException
     */
    abstract protected function validateConfig(): void;

    /**
     * Parse webhook data and return standardized webhook information
     *
     * @param array $webhookData
     * @return WebhookData
     */
    abstract public function parseWebhookData(array $webhookData): WebhookData;

    /**
     * Get the API base URL
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get the API headers
     *
     * @return array
     */
    protected function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Make an HTTP request
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @param array $headers
     * @return array|null
     */
    protected function makeRequest(string $method, string $endpoint, array $data = [], array $headers = []): ?array
    {
        try {
            $headers = array_merge($this->getHeaders(), $headers);
            $url = $this->getBaseUrl() . $endpoint;
            $client = new Client();
            $request = new Request($method, $url, $headers,!empty($data)?json_encode($data):null);
            $response = $client->send($request);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                return json_decode($response->getBody()->getContents(), true);
            }

            Log::error("Payment gateway API HTTP error", [
                'provider' => $this->getProviderName(),
                'status' => $response->getStatusCode(),
                'response' => $response->getBody()->getContents(),
                'url' => $url,
                'method' => $method
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error("Payment gateway API request error", [
                'provider' => $this->getProviderName(),
                'error' => $e->getMessage(),
                'url' => $url ?? null,
                'method' => $method
            ]);

            return null;
        }
    }

    /**
     * Create a successful payment response
     *
     * @param array $data
     * @return PaymentResponse
     */
    protected function createSuccessResponse(array $data): PaymentResponse
    {
        return new PaymentResponse(
            success: true,
            transactionId: $data['transaction_id'] ?? null,
            status: $data['status'] ?? 'success',
            paymentUrl: $data['payment_url'] ?? null,
            amount: $data['amount'] ?? null,
            currency: $data['currency'] ?? null,
            message: $data['message'] ?? 'Payment processed successfully',
            data: $data,
            metadata: $data['metadata'] ?? []
        );
    }

    /**
     * Create an error payment response
     *
     * @param string $message
     * @param string|null $errorCode
     * @param array|null $data
     * @return PaymentResponse
     */
    protected function createErrorResponse(string $message, ?string $errorCode = null, ?array $data = null): PaymentResponse
    {
        return new PaymentResponse(
            success: false,
            message: $message,
            errorCode: $errorCode,
            data: $data
        );
    }

    /**
     * Log payment activity
     *
     * @param string $action
     * @param array $data
     * @return void
     */
    protected function logActivity(string $action, array $data): void
    {
        Log::info("Payment gateway activity", [
            'provider' => $this->getProviderName(),
            'action' => $action,
            'data' => $data
        ]);
    }

    /**
     * Get configuration value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Check if provider supports a specific feature
     *
     * @param string $feature
     * @return bool
     */
    public function supportsFeature(string $feature): bool
    {
        return in_array($feature, $this->supportedFeatures);
    }

    /**
     * Get supported payment methods
     *
     * @return array
     */
    public function getSupportedPaymentMethods(): array
    {
        return $this->getConfig('supported_payment_methods', []);
    }
}
