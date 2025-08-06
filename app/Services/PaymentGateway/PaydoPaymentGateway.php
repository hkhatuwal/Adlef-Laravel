<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use Illuminate\Support\Str;

class PaydoPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'refunds',
        'webhooks',
        'recurring_payments',
        'multi_currency',
        'direct_integration'
    ];

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return 'paydo';
    }

    /**
     * Validate the configuration
     *
     * @throws \InvalidArgumentException
     */
    protected function validateConfig(): void
    {
        $requiredKeys = ['public_key', 'secret_key'];

        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("Paydo configuration missing required key: {$key}");
            }
        }

        // Paydo API base URL
        $this->baseUrl = $this->config['sandbox'] ?? false
            ? 'https://sandbox.paydo.com/v1'
            : 'https://api.paydo.com/v1';

        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
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

        // Generate unique order ID
        $generateInvoiceId = $this->generateInvoiceId($paymentData);


        // Add metadata if provided
        if (isset($paymentData['metadata'])) {
            $data['metadata'] = $paymentData['metadata'];
        }

        $response = $this->makeRequest('get', '/invoices/'.$generateInvoiceId);

        if ($response === null) {
            return $this->createErrorResponse('Failed to process payment', 'PAYDO_API_ERROR');
        }

        if (isset($response['error']) || (isset($response['status']) && $response['status'] === 'error')) {
            return $this->createErrorResponse(
                $response['message'] ?? $response['error']['message'] ?? 'Payment failed',
                $response['error']['code'] ?? 'PAYDO_ERROR',
                $response
            );
        }

        return $this->createSuccessResponse([
            'transaction_id' => $response['data']['orderIdentifier'],
            'status' => strtolower($response['data']['status'] ?? 'pending'),
            'amount' => $paymentData['amount'],
            'payment_url' => $this->getPaymentUrl($generateInvoiceId),
            'currency' => $paymentData['currency'] ?? 'USD',
            'message' => 'Payment order created successfully',
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

        $data = [
            'amount' => $amount,
            'currency' => strtoupper($options['currency'] ?? 'USD'),
            'description' => $options['description'] ?? 'Refund processed',
        ];

        if (isset($options['metadata'])) {
            $data['metadata'] = $options['metadata'];
        }

        $response = $this->makeRequest('post', "/transactions/{$transactionId}/refund", $data);

        if ($response === null) {
            return $this->createErrorResponse('Failed to process refund', 'PAYDO_API_ERROR');
        }

        if (isset($response['error']) || (isset($response['status']) && $response['status'] === 'error')) {
            return $this->createErrorResponse(
                $response['message'] ?? $response['error']['message'] ?? 'Refund failed',
                $response['error']['code'] ?? 'PAYDO_ERROR',
                $response
            );
        }

        return $this->createSuccessResponse([
            'transaction_id' => $response['data']['id'] ?? $response['id'],
            'status' => strtolower($response['data']['status'] ?? $response['status']),
            'amount' => $amount,
            'currency' => $options['currency'] ?? 'USD',
            'message' => 'Refund processed successfully',
            'raw_response' => $response
        ]);
    }

    /**
     * Get payment status
     *
     * @param string $transactionId
     * @return PaymentResponse
     */
    public function getPaymentStatus(string $transactionId): PaymentResponse
    {
        $response = $this->makeRequest('get', "/invoices/{$transactionId}");

        if ($response === null) {
            return $this->createErrorResponse('Failed to fetch payment status', 'PAYDO_API_ERROR');
        }

        if (isset($response['error']) || (isset($response['status']) && $response['status'] === 'error')) {
            return $this->createErrorResponse(
                $response['message'] ?? $response['error']['message'] ?? 'Failed to fetch payment status',
                $response['error']['code'] ?? 'PAYDO_ERROR',
                $response
            );
        }

        $data = $response['data'] ?? $response;

        return $this->createSuccessResponse([
            'transaction_id' => $data['id'],
            'status' => strtolower($data['status']),
            'amount' => ($data['amount'] ?? 0),
            'currency' => $data['currency'] ?? 'USD',
            'message' => 'Payment status retrieved successfully',
            'raw_response' => $response
        ]);
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
        // Paydo webhook signature verification
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
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
            'bank_transfer',
            'wallet',
            'crypto',
            'qiwi',
            'webmoney',
            'yandex_money',
            'perfect_money',
            'advcash',
            'payeer',
            'skrill',
            'neteller',
            'paysafecard',
            'mobile_payment'
        ];
    }

    /**
     * Generate signature for Paydo API
     *
     * @param string $orderId
     * @param int $amount
     * @param string $currency
     * @return string
     */
    protected function generateSignature(string $orderId, int $amount, string $currency): string
    {
        $data = [
            $amount,
            strtoupper($currency),
            $orderId,
            $this->config['secret_key']
        ];

        return hash('sha256', implode(':', $data));
    }

    /**
     * Create hosted payment page
     *
     * @param array $paymentData
     * @return PaymentResponse
     */
    public function createHostedPayment(array $paymentData): PaymentResponse
    {
        $this->logActivity('create_hosted_payment', $paymentData);

        $orderId = $paymentData['order_id'] ?? 'order_' . time() . '_' . Str::random(8);

        $data = [
            'publicKey' => $this->config['public_key'],
            'order' => [
                'id' => $orderId,
                'amount' => $paymentData['amount'],
                'currency' => strtoupper($paymentData['currency'] ?? 'USD'),
                'description' => $paymentData['description'] ?? 'Payment via Paydo',
            ],
            'payer' => [
                'email' => $paymentData['customer_email'] ?? '',
                'name' => $paymentData['customer_name'] ?? '',
            ],
            'language' => $paymentData['language'] ?? 'en',
            'resultUrl' => $paymentData['return_url'] ?? url('/payment/result'),
            'failPath' => $paymentData['cancel_url'] ?? url('/payment/cancel'),
            'signature' => $this->generateSignature($orderId, $paymentData['amount'], strtoupper($paymentData['currency'] ?? 'USD')),
        ];

        $response = $this->makeRequest('post', '/checkout/create', $data);


        if ($response === null) {
            return $this->createErrorResponse('Failed to create hosted payment', 'PAYDO_API_ERROR');
        }

        if (isset($response['error'])) {
            return $this->createErrorResponse(
                $response['error']['message'] ?? 'Failed to create hosted payment',
                $response['error']['code'] ?? 'PAYDO_ERROR',
                $response
            );
        }

        return $this->createSuccessResponse([
            'transaction_id' => $response['data']['id'] ?? $orderId,
            'status' => 'pending',
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'USD',
            'message' => 'Hosted payment created successfully',
            'payment_url' => $response['data']['url'] ?? $response['url'],
            'raw_response' => $response
        ]);
    }

    /**
     * Get available payment methods for a specific currency
     *
     * @param string $currency
     * @return array
     */
    public function getAvailablePaymentMethods(string $currency = 'USD'): array
    {
        $response = $this->makeRequest('get', '/payment-methods', [
            'currency' => strtoupper($currency)
        ]);

        if ($response === null || isset($response['error'])) {
            return $this->getSupportedPaymentMethods();
        }

        return $response['data'] ?? $response;
    }

    /**
     * Get supported currencies
     *
     * @return array
     */
    public function getSupportedCurrencies(): array
    {
        return [
            'USD', 'EUR', 'GBP', 'RUB', 'UAH', 'KZT', 'BYN',
            'PLN', 'CZK', 'BGN', 'RON', 'HUF', 'SEK', 'NOK',
            'DKK', 'CHF', 'CAD', 'AUD', 'JPY', 'CNY', 'INR',
            'BRL', 'MXN', 'ARS', 'CLP', 'PEN', 'COP', 'UYU',
            'BTC', 'ETH', 'LTC', 'BCH', 'XRP', 'USDT'
        ];
    }

    /**
     * Generates An Order Id For the Payment
     * @param array $paymentData
     * @return string
     * @throws \Exception
     */
    private function generateInvoiceId(array $paymentData): string
    {
        $orderId = $paymentData['order_id'];
        $data = [
            'publicKey' => $this->config['public_key'],
            'order' => [
                'id' => $orderId,
                'amount' => floatval($paymentData['amount']),
                'currency' => strtoupper($paymentData['currency'] ?? 'USD'),
                'description' => $paymentData['description'] ?? 'Payment via Paydo',
                'items' => $paymentData['items'] ?? [
                    [
                        'id' => '1',
                        'name' => $paymentData['description'] ?? 'Payment',
                        'price' => $paymentData['amount']
                    ]
                ]
            ],
            'payer' => [
                'email' => $paymentData['customer_email'] ?? '',
                'name' => $paymentData['customer_name'] ?? '',
                'phone' => $paymentData['customer_phone'] ?? '',
            ],
            'language' => $paymentData['language'] ?? 'en',
            'paymentMethod' => '700001',
            'resultUrl' => $paymentData['return_url'] ?? url('/payment/result'),
            'failPath' => $paymentData['cancel_url'] ?? url('/payment/cancel'),
            'signature' => $this->generateSignature($orderId, floatval($paymentData['amount']), strtoupper($paymentData['currency'] ?? 'USD')),
        ];

        $response = $this->makeRequest('post', '/invoices/create', $data);

        if ($response === null) {
            throw new \Exception( 'Failed to create payment');
        }

        return $response['data'];
    }

    /**
     * Get Payment Url
     * @param string $orderId
     * @return string
     * @throws \Exception
     */
    private function getPaymentUrl(string $orderId): string
    {
        return 'https://checkout.paydo.com/en-IN/payment/'.$orderId;
    }

    /**
     * Parse webhook data and return standardized webhook information
     *
     * @param array $webhookData
     * @return \App\Contracts\WebhookData
     * @throws \Exception
     */
    public function parseWebhookData(array $webhookData): \App\Contracts\WebhookData
    {
        // Extract transaction ID from Paydo webhook structure
        // Note: This structure may need to be adjusted based on actual Paydo webhook format
        $transactionId = $webhookData['order_id'] ?? $webhookData['transaction_id'] ?? null;
        if (!$transactionId) {
            throw new \Exception('Transaction ID not found in Paydo webhook data');
        }

        // Extract status from Paydo webhook structure
        $paydoStatus = $webhookData['status'] ?? null;
        if ($paydoStatus === null) {
            throw new \Exception('Status not found in Paydo webhook data');
        }

        // Map Paydo status to standardized status
        // Note: This mapping may need to be adjusted based on actual Paydo status values
        $status = match (strtolower($paydoStatus)) {
            'success', 'completed', 'paid' => 'completed',
            'failed', 'error', 'declined' => 'failed',
            'cancelled', 'canceled' => 'cancelled',
            default => 'pending',
        };

        // Extract additional data
        $amount = $webhookData['amount'] ?? null;
        $currency = $webhookData['currency'] ?? null;
        $gatewayTransactionId = $webhookData['gateway_transaction_id'] ?? $webhookData['id'] ?? null;
        $eventType = $webhookData['type'] ?? $webhookData['event'] ?? 'payment';

        return new \App\Contracts\WebhookData(
            transactionId: $transactionId,
            status: $status,
            amount: $amount ? (float) $amount : null,
            currency: $currency,
            gatewayTransactionId: $gatewayTransactionId,
            rawData: $webhookData,
            eventType: $eventType
        );
    }
}
