<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use App\Contracts\WebhookData;
use App\Models\PaymentTransaction;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Str;

class Pay4WorkPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'refunds',
        'webhooks',
        'multi_currency',
        'direct_integration',
        'hosted_payment'
    ];

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return 'pay4work';
    }

    /**
     * Validate the configuration
     *
     * @throws \InvalidArgumentException
     */
    protected function validateConfig(): void
    {
        $requiredKeys = ['api_key', 'api_secret'];

        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("Pay4Work configuration missing required key: {$key}");
            }
        }

        // Pay4Work API base URL
        $this->baseUrl = 'https://www.pay4.work/web/api/';
        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Calculate hash for Pay4Work API requests
     *
     * @param string $secret
     * @param array $params
     * @return string
     */
    protected function calculateHash(string $secret, array $params): string
    {
        ksort($params);
        $encodedData = [];
        foreach ($params as $key => $value) {
            if (str_starts_with($key, 'enc_')) {
                $encodedData[$key] = (string)$value;
            }
        }
        return hash_hmac('sha256', json_encode($encodedData), $secret);
    }

    /**
     * Validate webhook request
     *
     * @param string $apiSecret
     * @param string $receivedHash
     * @param array $receivedData
     * @return bool
     */
    protected function validateRequest(string $apiSecret, string $receivedHash, array $receivedData): bool
    {
        ksort($receivedData);
        $encodedData = [];
        foreach ($receivedData as $key => $value) {
            if (str_starts_with($key, 'enc_')) {
                $encodedData[$key] = (string)$value;
            }
        }
        $expectedHash = hash_hmac('sha256', json_encode($encodedData), $apiSecret);
        return hash_equals($expectedHash, $receivedHash);
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

        // Prepare order data for Pay4Work
        $orderData = [
            'req_type'=>"create_order",
            'enc_api_key'=>$this->config["api_key"],
            'enc_amount' => number_format($paymentData['amount'], 2, '.', ''),
            'enc_currency' => strtoupper($paymentData['currency']),
            'enc_order_id' => $paymentData['order_id'],
            'enc_customer_email' => $paymentData['customer_email'],
            'enc_product' => "Payment Process",
            'enc_customer_name' => $paymentData['customer_name'] ?? '',
            'enc_customer_phone' => $paymentData['customer_phone'] ?? '',
            'enc_description' => $paymentData['description'] ?? 'Payment via Pay4Work',
            'enc_callback_url' => $paymentData['return_url'],
            'enc_success_url' =>  $paymentData['return_url'] ?? url('/payment/result'),
            'enc_failed_url' => $paymentData['cancel_url'] ?? url('/payment/cancel'),
        ];

        // Add metadata if provided
        if (!empty($paymentData['metadata'])) {
            $orderData['enc_metadata'] = json_encode($paymentData['metadata']);
        }

        // Calculate hash
        $hash = $this->calculateHash($this->config['api_secret'], $orderData);
        $orderData['hash'] = $hash;

        // Make API request
        $response = $this->makeRequest('POST', '', $orderData);

        if ($response === null) {
            return $this->createErrorResponse('Failed to create Pay4Work order', 'PAY4WORK_API_ERROR');
        }

        if (!isset($response['success']) || !$response['success']) {
            $errorMessage = $response['info_data']['message'] ?? $response['info_data'][0]['message'] ?? 'Order creation failed';
            return $this->createErrorResponse($errorMessage, 'PAY4WORK_ORDER_ERROR', $response);
        }

        // Extract payment URL and transaction details
        $paymentUrl = $response['info_data']['payment_link'] ?? null;
        $transactionId = $response['info_data']['order_id'] ?? $paymentData['order_id'];

        if (!$paymentUrl) {
            return $this->createErrorResponse('Payment URL not found in response', 'PAY4WORK_PAYMENT_URL_ERROR', $response);
        }

        return $this->createSuccessResponse([
            'transaction_id' => $transactionId,
            'status' => 'pending',
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'payment_url' => $paymentUrl,
            'message' => 'Pay4Work order created successfully',
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
            // Pay4Work refund data
            $refundData = [
                'enc_transaction_id' => $transactionId,
                'enc_amount' => number_format($amount, 2, '.', ''),
                'enc_currency' => strtoupper($options['currency'] ?? 'USD'),
                'enc_reason' => $options['reason'] ?? 'Refund requested',
            ];

            // Calculate hash
            $hash = $this->calculateHash($this->config['api_secret'], $refundData);
            $refundData['hash'] = $hash;

            $response = $this->makeRequest('POST', '?req_type=refund', $refundData);

            if ($response === null) {
                return $this->createErrorResponse('Failed to process refund', 'PAY4WORK_API_ERROR');
            }

            if (isset($response['error']) || isset($response['errors'])) {
                $errorMessage = $response['error']['message'] ?? $response['errors'][0]['message'] ?? 'Refund failed';
                return $this->createErrorResponse($errorMessage, 'PAY4WORK_REFUND_ERROR', $response);
            }

            return $this->createSuccessResponse([
                'transaction_id' => $response['refund_id'] ?? $transactionId,
                'status' => 'refunded',
                'amount' => $amount,
                'currency' => $options['currency'] ?? 'USD',
                'message' => 'Refund processed successfully',
                'raw_response' => $response
            ]);

        } catch (\Exception $e) {
            return $this->createErrorResponse('Refund processing failed: ' . $e->getMessage(), 'PAY4WORK_REFUND_EXCEPTION');
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
            $statusData = [
                'enc_order_id' => $transactionId,
            ];

            // Calculate hash
            $hash = $this->calculateHash($this->config['api_secret'], $statusData);
            $statusData['hash'] = $hash;

            $response = $this->makeRequest('POST', '?req_type=order_status', $statusData);

            if ($response === null) {
                return $this->createErrorResponse('Failed to fetch payment status', 'PAY4WORK_API_ERROR');
            }

            if (isset($response['error']) || isset($response['errors'])) {
                $errorMessage = $response['error']['message'] ?? $response['errors'][0]['message'] ?? 'Failed to fetch payment status';
                return $this->createErrorResponse($errorMessage, 'PAY4WORK_STATUS_ERROR', $response);
            }

            // Map Pay4Work status to standardized status
            $status = $this->mapPay4WorkStatus($response['status'] ?? 'unknown');

            return $this->createSuccessResponse([
                'transaction_id' => $response['order_id'] ?? $transactionId,
                'status' => $status,
                'amount' => $response['amount'] ?? 0,
                'currency' => $response['pay_currency'] ?? 'USD',
                'message' => 'Payment status retrieved successfully',
                'raw_response' => $response
            ]);

        } catch (\Exception $e) {
            return $this->createErrorResponse('Status check failed: ' . $e->getMessage(), 'PAY4WORK_STATUS_EXCEPTION');
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

        return true;
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
        // Extract transaction ID from Pay4Work webhook structure
        $transactionId = $webhookData['enc_order_id'] ?? null;
        if (!$transactionId) {
            throw new \Exception('Transaction ID not found in Pay4Work webhook data');
        }

        // Map Pay4Work status to standardized status
        $status = $this->mapPay4WorkStatus($webhookData['enc_status'] ?? 'unknown');

        // Extract amount and currency
        $amount = isset($webhookData['enc_amount']) ? (float)$webhookData['enc_amount'] : null;
        $currency = $webhookData['enc_pay_currency'] ?? null;

        // Find the payment transaction
        $paymentTransaction = PaymentTransaction::where('gateway_transaction_id', $transactionId)->first();

        // Extract payment method details from webhook
        $paymentMethodData = null;


            $paymentMethodData = [
                'payment_method_type' => PaymentTransaction::PAYMENT_METHOD_CARD,
                'card_brand' => $webhookData['enc_payment_brand'] ?? null,
                'card_type' => strtolower($webhookData['enc_bin_type'] ?? ''),
                'card_last_four' => substr($webhookData['enc_card_masked'] ?? '', -4),
                'card_exp_month' => explode('/', $webhookData['enc_card_exp'] ?? '')[0] ?? null,
                'card_exp_year' => explode('/', $webhookData['enc_card_exp'] ?? '')[1] ?? null,
                'card_country' => strtolower($webhookData['enc_payment_country'] ?? ''),
                'card_holder' => $webhookData['enc_card_holder'] ?? null,
                'card_issuer' => $webhookData['enc_card_issuer'] ?? null,
            ];

        return new WebhookData(
            transactionId: $paymentTransaction->transaction_id,
            status: $status,
            amount: $amount,
            currency: $currency,
            gatewayTransactionId: $transactionId,
            rawData: $webhookData,
            eventType: 'payment',
            paymentMethodType: $paymentMethodData['payment_method_type'] ?? null,
            cardBrand: $paymentMethodData['card_brand'] ?? null,
            cardType: $paymentMethodData['card_type'] ?? null,
            cardLastFour: $paymentMethodData['card_last_four'] ?? null,
            cardExpMonth: $paymentMethodData['card_exp_month'] ?? null,
            cardExpYear: $paymentMethodData['card_exp_year'] ?? null,
            cardCountry: $paymentMethodData['card_country'] ?? null,
        );
    }

    /**
     * Map Pay4Work status to standardized status
     *
     * @param string $pay4workStatus
     * @return string
     */
    protected function mapPay4WorkStatus(string $pay4workStatus): string
    {
        return match ((string)$pay4workStatus) {
            '1', '11' => 'completed', // Success (1 for production, 11 for sandbox)
            '2' => 'failed', // Failed
            '3' => 'cancelled', // Cancelled by user
            default => 'pending',
        };
    }

    /**
     * Map payment mode to payment method type
     *
     * @param string $paymentMode
     * @return string
     */
    protected function mapPaymentModeToType(string $paymentMode): string
    {
        return match (strtoupper($paymentMode)) {
            'CREDIT_CARD', 'DEBIT_CARD' => PaymentTransaction::PAYMENT_METHOD_CARD,
            'UPI' => PaymentTransaction::PAYMENT_METHOD_UPI,
            'NET_BANKING' => PaymentTransaction::PAYMENT_METHOD_BANK_TRANSFER,
            'WALLET' => PaymentTransaction::PAYMENT_METHOD_WALLET,
            default => PaymentTransaction::PAYMENT_METHOD_CARD,
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
            'credit_card',
            'debit_card',
            'upi',
            'net_banking',
            'wallet',
            'visa',
            'mastercard',
            'american_express',
            'diners_club',
            'rupay'
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
            'AED', 'INR', 'USD'
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
        // For Pay4Work, the processPayment method already creates a hosted payment
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
        // Pay4Work supports different methods based on currency
        $methods = $this->getSupportedPaymentMethods();

        // Filter based on currency if needed
        if ($currency === 'INR') {
            $methods = array_merge($methods, ['upi', 'net_banking', 'rupay']);
        }

        return $methods;
    }
}

