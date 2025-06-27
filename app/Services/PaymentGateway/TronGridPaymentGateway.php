<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentResponse;
use App\Models\Currency;
use App\Services\TronService;
use App\Utils\CurrencyConverter;
use Illuminate\Support\Str;

class TronGridPaymentGateway extends AbstractPaymentGateway
{
    protected array $supportedFeatures = [
        'refunds',
        'webhooks',
        'direct_integration'
    ];


    protected CurrencyConverter $currencyConverter;

    protected TronService $tronService;

    /**
     * Get provider name
     *
     * @return string
     */


    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->tronService = new TronService();
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
        $requiredKeys = ['secret_key'];

        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("TRONGRID  configuration missing required key: {$key}");
            }
        }

        $this->baseUrl = $this->config['sandbox'] ?? false
            ? 'https://sandbox.payop.com/v1'
            : 'https://tron-api-production.up.railway.app';

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

        // Generate unique wallet address for this transaction
        $walletAddress = $this->generateTemporaryWallet();

        // Generate the payment URL for the crypto checkout page
        $paymentUrl = url('/payment/crypto/' . $walletAddress);

        $fromCurrency = Currency::where('symbol', $paymentData['currency'])->first();
        $toCurrency = Currency::where('symbol', "USDT")->first();

        $amount = $this->currencyConverter->convert($paymentData['amount'], $fromCurrency, $toCurrency);

        $response = [
            'wallet_address' => $walletAddress,
            'payment_url' => $paymentUrl,
            'amount' => $amount,
            'currency' => 'USDT',
            'network' => 'TRON',
            'expires_at' => now()->addMinutes(30)->toISOString(),
        ];

        return $this->createSuccessResponse([
            'transaction_id' => $walletAddress,
            'status' => 'pending',
            'amount' => $amount,
            'payment_url' => $paymentUrl,
            'currency' => 'USDT',
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
        return $this->createSuccessResponse([
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
     * Generates An Order Id For the Payment
     * @param array $paymentData
     * @return string
     * @throws \Exception
     */

    private function generateTemporaryWallet(): string
    {
        $wallet = $this->tronService->createTronAccount();

        return $wallet['address']['base58'];


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
}
