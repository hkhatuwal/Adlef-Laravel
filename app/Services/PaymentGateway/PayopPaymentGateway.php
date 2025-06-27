<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use Illuminate\Support\Str;

class PayopPaymentGateway extends AbstractPaymentGateway
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
        return 'payop';
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
                throw new \InvalidArgumentException("Payop configuration missing required key: {$key}");
            }
        }

        // Payop API base URL
        $this->baseUrl = $this->config['sandbox'] ?? false
            ? 'https://sandbox.payop.com/v1'
            : 'https://api.payop.com/v1';

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
        $generateInvoiceId =$this->generateInvoiceId($paymentData);

        $data = [
            'invoiceIdentifier' =>$generateInvoiceId,
            'customer' => [
                'email' => $paymentData['customer_email'] ?? '',
                'name' => $paymentData['customer_name'] ?? '',
                'phone' => $paymentData['customer_phone'] ?? '',
                'ip' => $paymentData['customer_ip'] ?? '192.168.1.1',
            ],
            'paymentMethod' => '700001',
            "checkStatusUrl"=> "https://your.site/check-status/{{txid}}"
        ];

        // Add metadata if provided
        if (isset($paymentData['metadata'])) {
            $data['metadata'] = $paymentData['metadata'];
        }

        $response = $this->makeRequest('post', '/checkout/create', $data);

        if ($response === null) {
            return $this->createErrorResponse('Failed to process payment', 'PAYOP_API_ERROR');
        }

        if (isset($response['error']) || (isset($response['status']) && $response['status'] === 'error')) {
            return $this->createErrorResponse(
                $response['message'] ?? $response['error']['message'] ?? 'Payment failed',
                $response['error']['code'] ?? 'PAYOP_ERROR',
                $response
            );
        }

        return $this->createSuccessResponse([
            'transaction_id' => $response['data']['txid'] ,
            'status' => strtolower($response['data']['isSuccess'] ?? 'pending'),
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
            'amount' => $amount , // Convert to cents
            'currency' => strtoupper($options['currency'] ?? 'USD'),
            'description' => $options['description'] ?? 'Refund processed',
        ];

        if (isset($options['metadata'])) {
            $data['metadata'] = $options['metadata'];
        }

        $response = $this->makeRequest('post', "/transactions/{$transactionId}/refund", $data);

        if ($response === null) {
            return $this->createErrorResponse('Failed to process refund', 'PAYOP_API_ERROR');
        }

        if (isset($response['error']) || (isset($response['status']) && $response['status'] === 'error')) {
            return $this->createErrorResponse(
                $response['message'] ?? $response['error']['message'] ?? 'Refund failed',
                $response['error']['code'] ?? 'PAYOP_ERROR',
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
            return $this->createErrorResponse('Failed to fetch payment status', 'PAYOP_API_ERROR');
        }

        if (isset($response['error']) || (isset($response['status']) && $response['status'] === 'error')) {
            return $this->createErrorResponse(
                $response['message'] ?? $response['error']['message'] ?? 'Failed to fetch payment status',
                $response['error']['code'] ?? 'PAYOP_ERROR',
                $response
            );
        }

        $data = $response['data'] ?? $response;

        return $this->createSuccessResponse([
            'transaction_id' => $data['id'],
            'status' => strtolower($data['status']),
            'amount' => ($data['amount'] ?? 0), // Convert from cents
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
        // Payop webhook signature verification
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
     * Generate signature for Payop API
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
                'amount' => $paymentData['amount'] ,
                'currency' => strtoupper($paymentData['currency'] ?? 'USD'),
                'description' => $paymentData['description'] ?? 'Payment via Payop',
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
            return $this->createErrorResponse('Failed to create hosted payment', 'PAYOP_API_ERROR');
        }

        if (isset($response['error'])) {
            return $this->createErrorResponse(
                $response['error']['message'] ?? 'Failed to create hosted payment',
                $response['error']['code'] ?? 'PAYOP_ERROR',
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
        $orderId=$paymentData['order_id'];
        $data = [
            'publicKey' => $this->config['public_key'],
            'order' => [
                'id' => $orderId,
                'amount' => floatval($paymentData['amount']) , // Convert to cents
                'currency' => strtoupper($paymentData['currency'] ?? 'USD'),
                'description' => $paymentData['description'] ?? 'Payment via Payop',
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
            'resultUrl' => $paymentData['return_url'] ?? url('/payment/result'),
            'failPath' => $paymentData['cancel_url'] ?? url('/payment/cancel'),
            'signature' => $this->generateSignature($orderId, floatval($paymentData['amount']), strtoupper($paymentData['currency'] ?? 'USD')),
        ];


      $response= $this->makeRequest('post', '/invoices/create', $data);

      if($response === null) {
        throw new \Exception('Failed to create  payment');
      }

      return  $response['data'];


    }


    /**
     * Get Payment Url
     * @param string $orderId
     * @return string
     * @throws \Exception
     */

    private function getPaymentUrl(string $orderId): string
    {
      $response= $this->makeRequest('get', '/checkout/check-invoice-status/'.$orderId);

      if($response === null) {
        throw new \Exception('Failed to get payment url');
      }

      return  $response['data']['url'];


    }









}
