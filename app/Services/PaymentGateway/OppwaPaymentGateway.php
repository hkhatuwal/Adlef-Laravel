<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use App\Contracts\WebhookData;
use App\Models\OppwaTransaction;
use App\Models\PaymentTransaction;
use App\Services\OppwaService;

class OppwaPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'refunds',
        'webhooks',
        'multi_currency',
        'direct_integration',
        'hosted_payment'
    ];

    private OppwaService $oppwaService;

    public function __construct(array $config)
    {
        $this->oppwaService = new OppwaService();
        parent::__construct($config);

    }

    /**
     * Get provider name
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return 'oppwa';
    }

    /**
     * Validate the configuration
     *
     * @throws \InvalidArgumentException
     */
    protected function validateConfig(): void
    {
        // OPPWA configuration is handled by OppwaService
        // We just need to ensure the service is properly configured
        $configStatus = $this->oppwaService->getConfigStatus();

        if ($configStatus['status'] !== 'ready') {
            throw new \InvalidArgumentException("OPPWA configuration is incomplete: " . json_encode($configStatus));
        }

        // Set base URL for compatibility with AbstractPaymentGateway
        $this->baseUrl = $configStatus['base_url'];
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

        try {
            // Create checkout session using OppwaService
            $result = $this->oppwaService->createCheckout($paymentData);

            if (!$result['success']) {
                return $this->createErrorResponse(
                    $result['error'] ?? 'Failed to create OPPWA checkout',
                    'OPPWA_CHECKOUT_ERROR',
                    $result
                );
            }

            return $this->createSuccessResponse([
                'transaction_id' => $result['transaction_id'],
                'status' => 'pending',
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'payment_url' => $result['payment_url'],
                'checkout_id' => $result['checkout_id'],
                'integrity' => $result['integrity'],
                'expires_at' => $result['expires_at'],
                'message' => 'OPPWA checkout session created successfully',
                'raw_response' => $result
            ]);

        } catch (\Exception $e) {
            return $this->createErrorResponse(
                'Payment processing failed: ' . $e->getMessage(),
                'OPPWA_PAYMENT_EXCEPTION'
            );
        }
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

        // OPPWA doesn't support direct refunds through their API
        // This would typically be handled through their merchant portal
        // or by contacting their support team

        return $this->createErrorResponse(
            'OPPWA refunds are not supported through the API. Please contact support or use the merchant portal.',
            'OPPWA_REFUND_NOT_SUPPORTED',
            [
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'currency' => $options['currency'] ?? 'USD'
            ]
        );
    }

    /**
     * Get payment status
     *
     * @param string $transactionId
     * @return PaymentResponse
     */
    public function getPaymentStatus(string $transactionId): PaymentResponse
    {
        $this->logActivity('get_payment_status', ['transaction_id' => $transactionId]);

        try {
            // First try to get transaction by transaction_id
            $transaction = $this->oppwaService->getTransaction($transactionId);

            if (!$transaction) {
                return $this->createErrorResponse(
                    'Transaction not found',
                    'OPPWA_TRANSACTION_NOT_FOUND'
                );
            }

            // If we have a checkout_id, get the latest status from OPPWA
            if ($transaction->oppwa_checkout_id) {
                $statusResult = $this->oppwaService->getPaymentStatus($transaction->oppwa_checkout_id);

                if (!$statusResult['success']) {
                    return $this->createErrorResponse(
                        $statusResult['error'] ?? 'Failed to get payment status',
                        'OPPWA_STATUS_ERROR',
                        $statusResult
                    );
                }

                $updatedTransaction = $statusResult['transaction'];
                $oppwaResponse = $statusResult['oppwa_response'];

                return $this->createSuccessResponse([
                    'transaction_id' => $updatedTransaction['transaction_id'],
                    'status' => $updatedTransaction['status'],
                    'amount' => $updatedTransaction['amount'],
                    'currency' => $updatedTransaction['currency'],
                    'oppwa_checkout_id' => $updatedTransaction['oppwa_checkout_id'],
                    'oppwa_payment_id' => $updatedTransaction['oppwa_payment_id'],
                    'message' => 'Payment status retrieved successfully',
                    'raw_response' => $oppwaResponse,
                    'transaction_details' => $updatedTransaction
                ]);
            }

            // Return current transaction status if no checkout_id
            return $this->createSuccessResponse([
                'transaction_id' => $transaction->transaction_id,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'message' => 'Transaction status retrieved from database',
                'transaction_details' => $transaction->toApiResponse()
            ]);

        } catch (\Exception $e) {
            return $this->createErrorResponse(
                'Status check failed: ' . $e->getMessage(),
                'OPPWA_STATUS_EXCEPTION'
            );
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
        return $this->oppwaService->verifyWebhookSignature($payload, $signature, $secret);
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
        // Extract transaction ID from webhook data
        $transactionId = $webhookData['transaction_id'] ?? null;
        if (!$transactionId) {
            throw new \Exception('Transaction ID not found in OPPWA webhook data');
        }

        // Get the transaction from database
        $transaction = $this->oppwaService->getTransaction($transactionId);
        if (!$transaction) {
            throw new \Exception("Transaction not found: {$transactionId}");
        }

        // Extract event type
        $eventType = $webhookData['event'] ?? 'unknown';

        // Map OPPWA status to standardized status
        $status = $this->mapOppwaStatusToStandard($transaction->status);

        // Extract payment method details if available
        $paymentMethodData = null;
        if ($transaction->oppwa_response && isset($transaction->oppwa_response['paymentMethod'])) {
            $paymentMethod = $transaction->oppwa_response['paymentMethod'];

            $paymentMethodData = [
                'payment_method_type' => PaymentTransaction::PAYMENT_METHOD_CARD,
                'card_brand' => $paymentMethod['brand'] ?? null,
                'card_type' => strtolower($paymentMethod['type'] ?? ''),
                'card_last_four' => substr($paymentMethod['maskedNumber'] ?? '', -4),
                'card_exp_month' => $paymentMethod['expiryMonth'] ?? null,
                'card_exp_year' => $paymentMethod['expiryYear'] ?? null,
                'card_country' => strtolower($paymentMethod['issuingCountry'] ?? ''),
            ];
        }

        return new WebhookData(
            transactionId: $transaction->transaction_id,
            status: $status,
            amount: $transaction->amount,
            currency: $transaction->currency,
            gatewayTransactionId: $transaction->oppwa_checkout_id,
            rawData: $webhookData,
            eventType: $eventType,
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
     * Map OPPWA status to standardized status
     *
     * @param string $oppwaStatus
     * @return string
     */
    protected function mapOppwaStatusToStandard(string $oppwaStatus): string
    {
        return match (strtoupper($oppwaStatus)) {
            OppwaTransaction::STATUS_COMPLETED => 'completed',
            OppwaTransaction::STATUS_PENDING => 'pending',
            OppwaTransaction::STATUS_PROCESSING => 'pending',
            OppwaTransaction::STATUS_FAILED => 'failed',
            OppwaTransaction::STATUS_CANCELLED => 'cancelled',
            OppwaTransaction::STATUS_EXPIRED => 'expired',
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
            'card' => ['visa', 'mastercard', 'amex', 'discover', 'diners'],
            'bank_transfer' => ['sepa', 'ach', 'wire'],
            'wallet' => ['paypal', 'apple_pay', 'google_pay'],
            'alternative' => ['klarna', 'afterpay', 'sezzle'],
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
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF', 'SEK', 'NOK', 'DKK',
            'PLN', 'CZK', 'HUF', 'BGN', 'RON', 'HRK', 'RUB', 'UAH', 'KZT', 'BYN',
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
        // For OPPWA, the processPayment method already creates a hosted payment
        return $this->processPayment($paymentData);
    }

    /**
     * Get available payment methods for a specific currency
     *
     * @param string $currency
     * @return array
     */
    public function getAvailablePaymentMethods(string $currency = 'USD'): array
    {
        return $this->getSupportedPaymentMethods();
    }

    /**
     * Check if currency is supported
     *
     * @param string $currency
     * @return bool
     */
    public function isCurrencySupported(string $currency): bool
    {
        return $this->oppwaService->isCurrencySupported($currency);
    }
}
