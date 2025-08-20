<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use App\Contracts\WebhookData;
use Dflydev\DotAccessData\Data;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;

class NgeniusPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'refunds',
        'webhooks',
        'multi_currency',
        'direct_integration',
        'hosted_payment'
    ];

    protected ?string $accessToken = null;
    protected int $tokenExpiresAt = 0;

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return 'ngenius';
    }

    /**
     * Validate the configuration
     *
     * @throws \InvalidArgumentException
     */
    protected function validateConfig(): void
    {
        $requiredKeys = ['api_key', 'outlet_reference'];

        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("N-Genius configuration missing required key: {$key}");
            }
        }

        // N-Genius API base URL
        $this->baseUrl = $this->config['sandbox'] ?? false
            ? 'https://api-gateway.sandbox.ngenius-payments.com'
            : 'https://api-gateway.ngenius-payments.com';

        $this->headers = [

        ];
    }

    /**
     * Get or refresh access token
     *
     * @return string
     * @throws \Exception
     */
    protected function getAccessToken(): string
    {
        // Check if we have a valid token
        if ($this->accessToken && time() < $this->tokenExpiresAt) {
            return $this->accessToken;
        }

        $this->logActivity('get_access_token', []);

        $response = $this->makeRequest('POST', '/identity/auth/access-token', [], [
            'Authorization' => 'Basic '.$this->config['api_key'],
            'Content-Type' => 'application/vnd.ni-identity.v1+json',
            'Accept' => 'application/vnd.ni-identity.v1+json',
        ]);


        if ($response === null || !isset($response['access_token'])) {
            throw new \Exception('Failed to obtain N-Genius access token');
        }

        $this->accessToken = $response['access_token'];
        // Set expiry time (subtract 30 seconds for safety)
        $this->tokenExpiresAt = time() + ($response['expires_in'] ?? 300) - 30;

        return $this->accessToken;
    }

    /**
     * Process a payment
     *
     * @param array $paymentData
     * @return PaymentResponse
     * @throws \Exception
     */
    public function processPayment(array $paymentData): PaymentResponse
    {
        $this->logActivity('process_payment', $paymentData);

        // Get access token
        $accessToken = $this->getAccessToken();

        // Prepare order data
        $orderData = [
            "action"=>"PURCHASE",
            'amount' => [
                'currencyCode' => strtoupper( 'AED'),
                'value' => (int)($paymentData['amount'] * 100), // Convert to minor units (cents)
            ],
        ];

        // Add optional fields
        if (!empty($paymentData['customer_email'])) {
            $orderData['emailAddress'] = $paymentData['customer_email'];
        }


        // Create order
        $outletReference = $this->config['outlet_reference'];
        $response = $this->makeRequest(
            'post',
            "/transactions/outlets/{$outletReference}/orders",
            $orderData,
            [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/vnd.ni-payment.v2+json',
                'Accept' => 'application/vnd.ni-payment.v2+json',
            ]
        );

        if ($response === null) {
            return $this->createErrorResponse('Failed to create N-Genius order', 'NGENIUS_API_ERROR');
        }

        if (isset($response['errors']) || isset($response['error'])) {
            $errorMessage = $response['errors'][0]['message'] ?? $response['error']['message'] ?? 'Order creation failed';
            return $this->createErrorResponse($errorMessage, 'NGENIUS_ORDER_ERROR', $response);
        }

        // Extract payment URL
        $paymentUrl = $response['_links']['payment']['href'] ?? null;
        $orderId = $response['_id'] ?? null;
        $reference = $response['reference'] ?? null;

        if (!$paymentUrl) {
            return $this->createErrorResponse('Payment URL not found in response', 'NGENIUS_PAYMENT_URL_ERROR', $response);
        }

        return $this->createSuccessResponse([
            'transaction_id' => $reference ?? $orderId,
            'status' => 'pending',
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'AED',
            'payment_url' => $paymentUrl,
            'message' => 'N-Genius order created successfully',
            'raw_response' => $response
        ]);
    }

    /**
     * Refund a payment
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return PaymentResponse
     */
    public function refundPayment(string $transactionId, float $amount, array $options = []): PaymentResponse
    {
        $this->logActivity('refund_payment', [
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'options' => $options
        ]);

        try {
            $accessToken = $this->getAccessToken();

            $refundData = [
                'amount' => [
                    'currencyCode' => strtoupper($options['currency'] ?? 'AED'),
                    'value' => (int)($amount * 100), // Convert to minor units
                ]
            ];

            if (isset($options['reason'])) {
                $refundData['reason'] = $options['reason'];
            }

            $outletReference = $this->config['outlet_reference'];
            $response = $this->makeRequest(
                'post',
                "/transactions/outlets/{$outletReference}/orders/{$transactionId}/refund",
                $refundData,
                [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/vnd.ni-payment.v2+json',
                    'Accept' => 'application/vnd.ni-payment.v2+json',
                ]
            );

            if ($response === null) {
                return $this->createErrorResponse('Failed to process refund', 'NGENIUS_API_ERROR');
            }

            if (isset($response['errors']) || isset($response['error'])) {
                $errorMessage = $response['errors'][0]['message'] ?? $response['error']['message'] ?? 'Refund failed';
                return $this->createErrorResponse($errorMessage, 'NGENIUS_REFUND_ERROR', $response);
            }

            return $this->createSuccessResponse([
                'transaction_id' => $response['_id'] ?? $transactionId,
                'status' => strtolower($response['state'] ?? 'pending'),
                'amount' => $amount,
                'currency' => $options['currency'] ?? 'AED',
                'message' => 'Refund processed successfully',
                'raw_response' => $response
            ]);

        } catch (\Exception $e) {
            return $this->createErrorResponse('Refund processing failed: ' . $e->getMessage(), 'NGENIUS_REFUND_EXCEPTION');
        }
    }

    /**
     * Get payment status
     *
     * @param string $transactionId
     * @return PaymentResponse
     */
    public function getPaymentStatus(string $transactionId): PaymentResponse
    {
        try {
            $accessToken = $this->getAccessToken();
            $outletReference = $this->config['outlet_reference'];

            $response = $this->makeRequest(
                'get',
                "/transactions/outlets/{$outletReference}/orders/{$transactionId}",
                [],
                [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => 'application/vnd.ni-payment.v2+json',
                ]
            );

            if ($response === null) {
                return $this->createErrorResponse('Failed to fetch payment status', 'NGENIUS_API_ERROR');
            }

            if (isset($response['errors']) || isset($response['error'])) {
                $errorMessage = $response['errors'][0]['message'] ?? $response['error']['message'] ?? 'Failed to fetch payment status';
                return $this->createErrorResponse($errorMessage, 'NGENIUS_STATUS_ERROR', $response);
            }

            // Map N-Genius state to standardized status
            $state = $response['state'] ?? 'unknown';
            $status = $this->mapNgeniusStatus($state);

            $amount = isset($response['amount']['value']) ? $response['amount']['value'] / 100 : 0;

            return $this->createSuccessResponse([
                'transaction_id' => $response['reference'] ?? $transactionId,
                'status' => $status,
                'amount' => $amount,
                'currency' => $response['amount']['currencyCode'] ?? 'AED',
                'message' => 'Payment status retrieved successfully',
                'raw_response' => $response
            ]);

        } catch (\Exception $e) {
            return $this->createErrorResponse('Status check failed: ' . $e->getMessage(), 'NGENIUS_STATUS_EXCEPTION');
        }
    }

    /**
     * Verify webhook signature
     *
     * @param string $payload
     * @param string $signature
     * @param string $secret
     * @return bool
     */
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        // N-Genius webhook signature verification
        // Note: This implementation may need to be adjusted based on actual N-Genius webhook signature method
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Parse webhook data and return standardized webhook information
     *
     * @param array $webhookData
     * @return WebhookData
     * @throws \Exception
     */
    public function parseWebhookData(array $webhookData): WebhookData
    {
        // Extract transaction ID from N-Genius webhook structure
        $transactionId = $webhookData['order']['reference'] ?? $webhookData['order']['id'] ?? null;
        if (!$transactionId) {
            throw new \Exception('Transaction ID not found in N-Genius webhook data');
        }

        // Extract event type
        $eventType = $webhookData['event'] ?? 'unknown';

        // Map N-Genius event to standardized status
        $status = $this->mapWebhookEventToStatus($eventType);

        // Extract additional data
        $amount = null;
        $currency = null;
        if (isset($webhookData['order']['amount'])) {
            $amount = $webhookData['order']['amount']['value'] / 100; // Convert from minor units
            $currency = $webhookData['order']['amount']['currencyCode'];
        }

        $gatewayTransactionId = $webhookData['transaction']['id'] ?? null;

        return new WebhookData(
            transactionId: $transactionId,
            status: $status,
            amount: $amount,
            currency: $currency,
            gatewayTransactionId: $gatewayTransactionId,
            rawData: $webhookData,
            eventType: $eventType
        );
    }

    /**
     * Map N-Genius status to standardized status
     *
     * @param string $ngeniusStatus
     * @return string
     */
    protected function mapNgeniusStatus(string $ngeniusStatus): string
    {
        return match (strtoupper($ngeniusStatus)) {
            'CAPTURED', 'PURCHASED' => 'completed',
            'AUTHORISED' => 'authorized',
            'DECLINED', 'PURCHASE_DECLINED' => 'failed',
            'CANCELLED', 'VOIDED' => 'cancelled',
            'REFUNDED', 'PARTIALLY_REFUNDED' => 'refunded',
            'STARTED', 'PENDING' => 'pending',
            default => 'pending',
        };
    }

    /**
     * Map webhook event to status
     *
     * @param string $event
     * @return string
     */
    protected function mapWebhookEventToStatus(string $event): string
    {
        return match (strtoupper($event)) {
            'AUTHORISED' => 'authorized',
            'PURCHASED', 'CAPTURED' => 'completed',
            'DECLINED', 'PURCHASE_DECLINED', 'AUTHORISATION_FAILED', 'PURCHASE_FAILED' => 'failed',
            'CANCELLED', 'FULL_AUTH_REVERSED', 'PURCHASE_REVERSED' => 'cancelled',
            'REFUNDED', 'PARTIALLY_REFUNDED' => 'refunded',
            default => 'pending',
        };
    }

    /**
     * Get supported payment methods
     *
     * @return array
     */
    public function getSupportedPaymentMethods(): array
    {
        return [
            'card',
            'wallet',
            'apple_pay',
            'samsung_pay',
            'visa',
            'mastercard',
            'american_express',
            'diners_club'
        ];
    }

    /**
     * Get supported currencies
     *
     * @return array
     */
    public function getSupportedCurrencies(): array
    {
        return [
            'AED', 'USD', 'EUR', 'GBP', 'SAR', 'KWD', 'BHD', 'OMR', 'QAR',
            'JOD', 'EGP', 'LBP', 'TND', 'MAD', 'DZD', 'IQD', 'LYD', 'SDG',
            'SYP', 'YER', 'MRU', 'SOS', 'DJF', 'KMF', 'XOF', 'XAF', 'CDF',
            'ETB', 'RWF', 'UGX', 'KES', 'TZS', 'MWK', 'ZMW', 'BWP', 'SZL',
            'LSL', 'NAD', 'ZAR', 'MZN', 'AOA', 'CVE', 'GHS', 'GMD', 'GNF',
            'LRD', 'SLL', 'NGN', 'XOF', 'BIF', 'SCR', 'MUR', 'MGA', 'KMF'
        ];
    }

    /**
     * Create hosted payment page
     *
     * @param array $paymentData
     * @return PaymentResponse
     */
    public function createHostedPayment(array $paymentData): PaymentResponse
    {
        // For N-Genius, the processPayment method already creates a hosted payment
        return $this->processPayment($paymentData);
    }

    /**
     * Get available payment methods for a specific currency
     *
     * @param string $currency
     * @return array
     */
    public function getAvailablePaymentMethods(string $currency = 'AED'): array
    {
        // This would typically require an API call to get available methods for currency
        // For now, return the supported methods
        return $this->getSupportedPaymentMethods();
    }
}
