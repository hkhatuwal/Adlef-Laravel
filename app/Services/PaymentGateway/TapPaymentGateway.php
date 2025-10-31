<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use App\Contracts\WebhookData as WebhookDataContract;
use Illuminate\Support\Facades\Log;

class TapPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'webhooks',
        'multi_currency',
        'direct_integration',
        'hosted_payment',
        'authorization'
    ];

    /**
     * Get provider name
     */
    public function getProviderName(): string
    {
        return 'tap';
    }

    /**
     * Validate configuration and set base URL and headers
     */
    protected function validateConfig(): void
    {
        $requiredKeys = ['secret_key'];
        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("Tap configuration missing required key: {$key}");
            }
        }

        $this->baseUrl = 'https://api.tap.company/v2';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->config['secret_key'],
        ];
    }

    /**
     * Process a payment by creating an authorization with Tap
     */
    public function processPayment(array $paymentData): PaymentResponse
    {
        $this->logActivity('process_payment', $paymentData);

        $amount = (float)($paymentData['amount'] ?? 0);
        $currency = strtoupper($paymentData['currency'] ?? 'KWD');
        $orderId = $paymentData['order_id'] ?? ('ord_' . time());
        $transactionRef = $paymentData['transaction_ref'] ?? ('txn_' . $orderId);

        $data = [
            'amount' => $amount,
            'currency' => $currency,
            'customer_initiated' => true,
            'threeDSecure' => true,
            'save_card' => false,
            'statement_descriptor' => $paymentData['description'] ?? 'Payment',
            'receipt' => [
                'email' => (bool)($paymentData['receipt_email'] ?? true),
                'sms' => (bool)($paymentData['receipt_sms'] ?? false),
            ],
            'metadata' => $paymentData['metadata'] ?? [],
            'reference' => [
                'transaction' => $transactionRef,
                'order' => $orderId,
            ],
            'customer' => [
                'first_name' => $paymentData['customer_first_name'] ?? ($paymentData['customer_name'] ?? ''),
                'last_name' => $paymentData['customer_last_name'] ?? '',
                'email' => $paymentData['customer_email'] ?? '',
                'phone' => [
                    'country_code' => $paymentData['customer_phone_country_code'] ?? '',
                    'number' => $paymentData['customer_phone'] ?? ''
                ],
            ],
            'merchant' => [
                'id' => $this->config['merchant_id'] ?? null,
            ],
            'source' => [
                // For card redirection flow
                'id' => $paymentData['source_id'] ?? 'src_card',
            ],
            'authorize_debit' => false,
            'auto' => [
                'type' => 'VOID',
                'time' => (int)($paymentData['auto_void_time'] ?? 100),
            ],
            'post' => [
                'url' => $paymentData['webhook_url'] ?? url('/api/webhooks/tap'),
            ],
            'redirect' => [
                'url' => $paymentData['return_url'] ?? url('/payment/tap/redirect'),
            ],
        ];

        $response = $this->makeRequest('post', '/authorize/', $data);

        if ($response === null) {
            return $this->createErrorResponse('Failed to process payment', 'TAP_API_ERROR');
        }

        if (isset($response['errors']) || (isset($response['response']['code']) && $response['response']['code'] !== '100')) {
            return $this->createErrorResponse(
                $response['response']['message'] ?? 'Payment initiation failed',
                $response['response']['code'] ?? 'TAP_ERROR',
                $response
            );
        }

        return $this->createSuccessResponse([
            'transaction_id' => $response['id'] ?? null,
            'status' => strtolower($response['status'] ?? 'initiated'),
            'amount' => $amount,
            'currency' => $currency,
            'payment_url' => $response['transaction']['url'] ?? null,
            'message' => 'Authorization created successfully',
            'raw_response' => $response,
        ]);
    }

    /**
     * Refund a payment (not implemented for Tap in this integration)
     */
    public function refundPayment(string $transactionId, float $amount, array $options = []): PaymentResponse
    {
        return $this->createErrorResponse('Refund not implemented for Tap gateway', 'TAP_REFUND_UNSUPPORTED');
    }

    /**
     * Get payment status for an authorization
     */
    public function getPaymentStatus(string $transactionId): PaymentResponse
    {
        $response = $this->makeRequest('get', '/authorize/' . $transactionId);

        if ($response === null) {
            return $this->createErrorResponse('Failed to fetch payment status', 'TAP_API_ERROR');
        }

        if (isset($response['errors'])) {
            return $this->createErrorResponse('Failed to fetch payment status', 'TAP_ERROR', $response);
        }

        $amount = $response['transaction']['amount'] ?? $response['amount'] ?? null;
        $currency = $response['transaction']['currency'] ?? $response['currency'] ?? null;

        return $this->createSuccessResponse([
            'transaction_id' => $response['id'] ?? $transactionId,
            'status' => strtolower($response['status'] ?? 'pending'),
            'amount' => $amount,
            'currency' => $currency,
            'message' => 'Payment status retrieved successfully',
            'raw_response' => $response,
        ]);
    }

    /**
     * Verify webhook signature (basic HMAC-SHA256 of payload using secret key)
     */
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        $expected = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expected, $signature);
    }

    /**
     * Parse webhook data to standardized structure
     */
    public function parseWebhookData(array $webhookData): WebhookDataContract
    {
        $orderId = $webhookData['reference']['order'] ?? null;
        $merchantTxn = $webhookData['reference']['transaction'] ?? null;
        $authId = $webhookData['id'] ?? null;

        $transactionId = $orderId ?? $merchantTxn ?? $authId;

        $tapStatus = strtoupper($webhookData['status'] ?? 'PENDING');
        $status = match ($tapStatus) {
            'CAPTURED', 'APPROVED', 'AUTHORIZED', 'SUCCESS' => 'completed',
            'FAILED', 'DECLINED' => 'failed',
            'CANCELLED', 'CANCELED' => 'cancelled',
            'INITIATED', 'PENDING' => 'pending',
            default => strtolower($tapStatus),
        };

        $amount = $webhookData['transaction']['amount'] ?? $webhookData['amount'] ?? null;
        $currency = $webhookData['transaction']['currency'] ?? $webhookData['currency'] ?? null;
        $failureReason =$status=="failed"? ($webhookData['response']['message'] ?? null):null;
        $gatewayTransactionId = $authId;
        $eventType = $webhookData['object'] ?? 'authorize';

        return new \App\Contracts\WebhookData(
            transactionId: (string)$transactionId,
            status: $status,
            amount: $amount ? (float)$amount : null,
            currency: $currency, gatewayTransactionId: $gatewayTransactionId,
            rawData: $webhookData,
            failureReason: $failureReason,
            eventType: (string)$eventType
        );
    }

    /**
     * Supported payment methods
     */
    public function getSupportedPaymentMethods(): array
    {
        return [
            'card',
        ];
    }
}


