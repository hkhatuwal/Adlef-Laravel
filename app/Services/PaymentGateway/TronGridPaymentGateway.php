<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use App\Models\Currency;
use App\Models\CryptoPaymentOrder;
use App\Utils\CurrencyConverter;
use Illuminate\Support\Str;

class TronGridPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'refunds',
        'webhooks',
        'direct_integration'
    ];

    /**
     * Number of decimal places for fingerprint amount
     * Can be configured for future changes
     */
    protected int $fingerprintDecimals = 1;

    protected CurrencyConverter $currencyConverter;

    /**
     * Get provider name
     *
     * @return string
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->currencyConverter = new CurrencyConverter();
    }

    public function getProviderName(): string
    {
        return 'trongrid';
    }

    /**
     * Validate the configuration
     *
     * @throws \InvalidArgumentException
     */
    protected function validateConfig(): void
    {
        $requiredKeys = ['secret_key', 'main_wallet_address'];

        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("TRONGRID configuration missing required key: {$key}");
            }
        }

        // Validate that the main wallet address is a valid TRON address
        if (!$this->isValidTronAddress($this->config['main_wallet_address'])) {
            throw new \InvalidArgumentException("Invalid TRON wallet address provided");
        }

        $this->baseUrl = $this->config['sandbox'] ?? false
            ? 'https://sandbox.trongrid.io'
            : 'https://api.trongrid.io';

        $this->headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Validate TRON wallet address format
     */
    private function isValidTronAddress(string $address): bool
    {
        // Basic TRON address validation (starts with T and is 34 characters)
        return strlen($address) === 34 && $address[0] === 'T';
    }


        /**
     * Generate a fingerprinted amount to uniquely identify payments
     * Incrementally adds decimal to avoid collisions with existing pending payments
     *
     * @param float $initialAmount
     * @return array ['amount' => float, 'fingerprint' => string]
     */
    private function getFingerprintAmount(float $initialAmount): array
    {
        $walletAddress = $this->config['main_wallet_address'];
        $increment = 1 / pow(10, $this->fingerprintDecimals); // e.g., 0.1 for 1 decimal, 0.01 for 2 decimals
        $maxAttempts = pow(10, $this->fingerprintDecimals) * 10; // Reasonable limit to avoid infinite loops

        $currentAmount = $initialAmount;
        $attempts = 0;

        // Keep incrementing until we find an unused amount
        while ($attempts < $maxAttempts) {
            $roundedAmount = round($currentAmount, $this->fingerprintDecimals);

            // Check if this amount is already used by a pending payment
            if (!$this->isAmountAlreadyUsed($roundedAmount, $walletAddress)) {
                // Calculate the fingerprint (decimal part)
                $decimalPart = $roundedAmount - $initialAmount;

                return [
                    'amount' => $roundedAmount,
                    'fingerprint' => $decimalPart
                ];
            }

            $currentAmount += $increment;
            $attempts++;
        }

        // Fallback: if we can't find an unused amount, use timestamp-based fingerprint
        $timestampFingerprint = substr(str_replace('.', '', microtime(true)), -$this->fingerprintDecimals);
        $fallbackAmount = $initialAmount + ($timestampFingerprint / pow(10, $this->fingerprintDecimals));
        return [
            'amount' => round($fallbackAmount, $this->fingerprintDecimals),
            'fingerprint' => $timestampFingerprint
        ];
    }

    /**
     * Check if the given amount is already used by a pending payment
     *
     * @param float $amount
     * @param string $walletAddress
     * @return bool
     */
    private function isAmountAlreadyUsed(float $amount, string $walletAddress): bool
    {
        return CryptoPaymentOrder::where('fingerprint_amount', $amount)
            ->where('wallet_address', $walletAddress)
            ->where('status', CryptoPaymentOrder::STATUS_PENDING)
            ->exists();
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

        // Use main wallet address from config
        $walletAddress = $this->config['main_wallet_address'] ?? 'TQn9Y2khEsLJW1ChVWFMSMeRDow5KcbLSE'; // Default TRON USDT address

        // Convert currency if needed
        $fromCurrency = Currency::where('symbol', $paymentData['currency'] ?? 'USD')->first();
        $toCurrency = Currency::where('symbol', "USDT")->first();

        if (!$fromCurrency || !$toCurrency) {
            throw new \Exception('Currency not found for conversion');
        }

        $convertedAmount = $this->currencyConverter->convert($paymentData['amount'], $fromCurrency, $toCurrency);

        // Generate fingerprinted amount
        $fingerprintData = $this->getFingerprintAmount($convertedAmount);

        // Generate unique order ID
        $orderId =$paymentData['order_id']?? CryptoPaymentOrder::generateOrderId();

        // Create payment order record
        $paymentOrder = CryptoPaymentOrder::create([
            'order_id' =>$orderId,
            'gateway_name' => 'trongrid',
            'wallet_address' => $walletAddress,
            'original_amount' => $paymentData['amount'],
            'fingerprint_amount' => $fingerprintData['amount'],
            'fingerprint_code' => $fingerprintData['fingerprint'],
            'original_currency' => $paymentData['currency'] ?? 'USD',
            'payment_currency' => 'USDT',
            'network' => 'TRON',
            'customer_email' => $paymentData['customer_email'] ?? null,
            'customer_name' => $paymentData['customer_name'] ?? null,
            'customer_phone' => $paymentData['customer_phone'] ?? null,
            'description' => $paymentData['description'] ?? 'Crypto payment via TronGrid',
            'metadata' => $paymentData['metadata'] ?? null,
            'items' => $paymentData['items'] ?? null,
            'return_url' => $paymentData['return_url'] ?? null,
            'cancel_url' => $paymentData['cancel_url'] ?? null,
            'payment_url' => url('/payment/crypto/' . $orderId),
            'expires_at' => now()->addMinutes(30),
            'api_client_id' => $paymentData['api_client_id'] ?? null,
        ]);

        $response = [
            'order_id' => $orderId,
            'wallet_address' => $walletAddress,
            'payment_url' => $paymentOrder->payment_url,
            'original_amount' => $paymentData['amount'],
            'fingerprint_amount' => $fingerprintData['amount'],
            'currency' => 'USDT',
            'network' => 'TRON',
            'expires_at' => $paymentOrder->expires_at->toISOString(),
        ];

        return $this->createSuccessResponse([
            'transaction_id' => $orderId,
            'status' => 'pending',
            'amount' => $fingerprintData['amount'],
            'payment_url' => $paymentOrder->payment_url,
            'currency' => 'USDT',
            'message' => 'Payment order created successfully. Please send exactly ' . $fingerprintData['amount'] . ' USDT to the provided wallet address: ' . $walletAddress,
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
        return $this->createSuccessResponse([
        ]);
    }

    /**
     * Get payment status
     *
     * @param string $transactionId - Our internal order ID
     * @return PaymentResponse
     */
    public function getPaymentStatus(string $transactionId): PaymentResponse
    {
        // Find the payment order by our internal order ID
        $paymentOrder = CryptoPaymentOrder::where('order_id', $transactionId)->first();

        if (!$paymentOrder) {
            return $this->createErrorResponse('Payment order not found', 'ORDER_NOT_FOUND');
        }

        // Check if payment has expired
        if ($paymentOrder->isExpired()) {
            $paymentOrder->markAsExpired();
        }

        // Map our internal status to gateway status
        $gatewayStatus = match ($paymentOrder->status) {
            CryptoPaymentOrder::STATUS_PENDING => 'pending',
            CryptoPaymentOrder::STATUS_PAID => 'paid',
            CryptoPaymentOrder::STATUS_COMPLETED => 'completed',
            CryptoPaymentOrder::STATUS_FAILED => 'failed',
            CryptoPaymentOrder::STATUS_CANCELLED => 'cancelled',
            CryptoPaymentOrder::STATUS_EXPIRED => 'expired',
            default => 'pending',
        };

        return $this->createSuccessResponse([
            'transaction_id' => $paymentOrder->order_id,
            'status' => $gatewayStatus,
            'amount' => $paymentOrder->fingerprint_amount,
            'original_amount' => $paymentOrder->original_amount,
            'currency' => $paymentOrder->payment_currency,
            'original_currency' => $paymentOrder->original_currency,
            'wallet_address' => $paymentOrder->wallet_address,
            'payment_url' => $paymentOrder->payment_url,
            'expires_at' => $paymentOrder->expires_at?->toISOString(),
            'paid_at' => $paymentOrder->paid_at?->toISOString(),
            'completed_at' => $paymentOrder->completed_at?->toISOString(),
            'gateway_transaction_id' => $paymentOrder->gateway_transaction_id,
            'fingerprint_code' => $paymentOrder->fingerprint_code,
            'message' => 'Payment status retrieved successfully',
            'raw_response' => [
                'order_details' => $paymentOrder->toArray(),
                'is_expired' => $paymentOrder->isExpired(),
                'is_awaiting_payment' => $paymentOrder->isAwaitingPayment(),
            ]
        ]);
    }

    /**
     * Get supported payment methods
     *
     * @return array
     */
    public function getSupportedPaymentMethods(): array
    {
        return [
            'crypto',
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
            'USD', 'EUR', 'GBP', 'RUB', 'UAH', 'KZT', 'BYN',
            'PLN', 'CZK', 'BGN', 'RON', 'HUF', 'SEK', 'NOK',
            'DKK', 'CHF', 'CAD', 'AUD', 'JPY', 'CNY', 'INR',
            'BRL', 'MXN', 'ARS', 'CLP', 'PEN', 'COP', 'UYU',
            'BTC', 'ETH', 'LTC', 'BCH', 'XRP', 'USDT'
        ];
    }





    /**
     * Get Payment Url
     * @param string $orderId
     * @return string
     * @throws \Exception
     */

    private function getPaymentUrl(string $orderId): string
    {
        $response = $this->makeRequest('get', '/checkout/check-invoice-status/' . $orderId);

        if ($response === null) {
            throw new \Exception('Failed to get payment url');
        }

        return $response['data']['url'];


    }


    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        return true;
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

        return new \App\Contracts\WebhookData(
            transactionId: $webhookData['transaction_id'], // Use our internal order ID
            status: $webhookData['status'],
            amount: $webhookData['amount'],
            currency:$webhookData['currency'],
            gatewayTransactionId:$webhookData['transaction_id'],
            rawData: $webhookData,
            eventType: $webhookData['type'] ?? $webhookData['event'] ?? 'payment'
        );
    }
}
