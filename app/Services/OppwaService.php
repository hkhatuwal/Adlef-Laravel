<?php

namespace App\Services;

use App\Models\OppwaTransaction;
use App\Models\ApiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class OppwaService
{
    private string $baseUrl;
    private string $entityId;
    private string $authBearer;
    private bool $sandbox;

    public function __construct()
    {
        $this->baseUrl = config('services.oppwa.base_url', 'https://eu-prod.oppwa.com');
        $this->entityId = config('services.oppwa.entity_id');
        $this->authBearer = config('services.oppwa.auth_bearer');
        $this->sandbox = config('services.oppwa.sandbox', false);
    }

    /**
     * Create a checkout session with OPPWA
     */
    public function createCheckout(array $paymentData): array
    {
        try {
            $transactionId = OppwaTransaction::generateTransactionId();

            // Create transaction record
            $transaction = OppwaTransaction::create([
                'transaction_id' => $transactionId,
                'external_order_id' => $paymentData['external_order_id'] ?? null,
                'api_client_id' => $paymentData['api_client_id'] ?? null,
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'payment_type' => $paymentData['payment_type'] ?? 'DB',
                'description' => $paymentData['description'] ?? null,
                'customer_email' => $paymentData['customer_email'] ?? null,
                'customer_name' => $paymentData['customer_name'] ?? null,
                'customer_phone' => $paymentData['customer_phone'] ?? null,
                'result_url' => $paymentData['return_url'] ?? null,
                'callback_url' => route('webhooks.oppwa'),
                'status' => OppwaTransaction::STATUS_PENDING,
                'ip_address' => $paymentData['ip_address'] ?? null,
                'user_agent' => $paymentData['user_agent'] ?? null,
                'metadata' => $paymentData['metadata'] ?? null,
                'expires_at' => now()->addHours(24), // OPPWA checkout sessions typically expire in 24 hours
            ]);

            // Prepare OPPWA request
            $oppwaData = [
                'entityId' => $this->entityId,
                'amount' => number_format($paymentData['amount'], 2, '.', ''),
                'currency' => $paymentData['currency'],
                'paymentType' => $paymentData['payment_type'] ?? 'DB',
                'integrity' => 'true',
            ];

            // Add optional parameters
            if (isset($paymentData['description'])) {
                $oppwaData['merchantTransactionId'] = $transactionId;
            }

            // Make request to OPPWA
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->authBearer,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($this->baseUrl . '/v1/checkouts', $oppwaData);

            $responseData = $response->json();

            if (!$response->successful()) {
                $transaction->updateStatus(
                    OppwaTransaction::STATUS_FAILED,
                    $responseData,
                    'Failed to create OPPWA checkout session'
                );

                throw new Exception('OPPWA checkout creation failed: ' . ($responseData['result']['description'] ?? 'Unknown error'));
            }

            // Update transaction with OPPWA response
            $checkoutId = $responseData['id'] ?? null;
            $integrity = $responseData['integrity']['value'] ?? null;

            $transaction->update([
                'oppwa_checkout_id' => $checkoutId,
                'oppwa_response' => $responseData,
                'payment_url' => $this->generatePaymentUrl($transactionId),
            ]);

            Log::channel('oppwa')->info('OPPWA checkout created successfully', [
                'transaction_id' => $transactionId,
                'oppwa_checkout_id' => $checkoutId
            ]);

            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'checkout_id' => $checkoutId,
                'payment_url' => $transaction->payment_url,
                'integrity' => $integrity,
                'expires_at' => $transaction->expires_at->toISOString(),
            ];

        } catch (Exception $e) {
            Log::channel('oppwa')->error('OPPWA checkout creation failed', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment status from OPPWA
     */
    public function getPaymentStatus(string $checkoutId): array
    {
        try {
            Log::channel('oppwa')->info('Searching for payment '. $checkoutId);

            $transaction = OppwaTransaction::where('oppwa_checkout_id', $checkoutId)->first();

            if (!$transaction) {
                throw new Exception('Transaction not found');
            }

            // Make request to OPPWA
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->authBearer,
            ])->get($this->baseUrl . '/v1/checkouts/' . $checkoutId . '/payment', [
                'entityId' => $this->entityId,
            ]);

            $responseData = $response->json();

            Log::channel('oppwa')->info('OPPWA transaction response '. $checkoutId);
            Log::channel('oppwa')->info($responseData);

            if (!$response->successful()) {
                throw new Exception('Failed to get payment status: ' . ($responseData['result']['description'] ?? 'Unknown error'));
            }

            // Update transaction status based on OPPWA response
            $newStatus = OppwaTransaction::determineStatusFromOppwaResponse($responseData);

            // Get detailed failure reason if failed
            $failureReason = null;
            if ($newStatus === OppwaTransaction::STATUS_FAILED) {
                $resultCode = $responseData['result']['code'] ?? 'unknown';
                $failureReason = OppwaTransaction::getOppwaStatusDescription($resultCode);
            }

            /* Only call the webhook if the status is new */
            Log::channel('oppwa')->info('OPPWA transaction status updated successfully', [
                "newStatus" => $newStatus,
                "oldStatus" => $transaction->status,
            ]);

            $oldStatus=$transaction->status;
            $transaction=$transaction->updateStatus($newStatus, $responseData, $failureReason);
            $transaction=OppwaTransaction::find($transaction->id);
            if ($newStatus!=$oldStatus) {
                $this->callWebhook($transaction);
            }

            $statusAnalysis = $this->analyzeOppwaResponse($responseData);

            Log::channel('oppwa')->info('OPPWA payment status retrieved', [
                'transaction_id' => $transaction->transaction_id,
                'checkout_id' => $checkoutId,
                'status' => $newStatus,
                'oppwa_status' => $responseData['result']['code'] ?? null,
                'status_analysis' => $statusAnalysis,
                'requires_manual_review' => $statusAnalysis['requires_manual_review'] ?? false,
            ]);

            return [
                'success' => true,
                'transaction' => $transaction->toApiResponse(),
                'oppwa_response' => $responseData,
            ];

        } catch (Exception $e) {
            Log::channel('oppwa')->error('OPPWA payment status check failed', [
                'error' => $e->getMessage(),
                'checkout_id' => $checkoutId
            ]);


            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get transaction by ID
     */
    public function getTransaction(string $transactionId): ?OppwaTransaction
    {
        return OppwaTransaction::where('transaction_id', $transactionId)->first();
    }

    /**
     * Get transaction by external order ID
     */
    public function getTransactionByExternalOrderId(string $externalOrderId): ?OppwaTransaction
    {
        return OppwaTransaction::where('external_order_id', $externalOrderId)->first();
    }

    /**
     * Generate payment URL for OPPWA widget
     */
    private function generatePaymentUrl($transactionId): string
    {


        return route('oppwa.payment',['transactionId' =>$transactionId]);
    }

    /**
     * Verify webhook signature (if OPPWA provides signature verification)
     */
    public function verifyWebhookSignature(string $payload, string $signature, string $secret): bool
    {
        // OPPWA may not provide signature verification, but we can implement it if needed
        // For now, we'll return true and rely on HTTPS and IP whitelisting
        return true;
    }


    /**
     * Get supported payment methods
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
     */
    public function getSupportedCurrencies(): array
    {
        return [
            'USD', 'EUR', 'GBP', 'CAD', 'AUD', 'JPY', 'CHF', 'SEK', 'NOK', 'DKK',
            'PLN', 'CZK', 'HUF', 'BGN', 'RON', 'HRK', 'RUB', 'UAH', 'KZT', 'BYN',
        ];
    }

    /**
     * Check if currency is supported
     */
    public function isCurrencySupported(string $currency): bool
    {
        return in_array(strtoupper($currency), $this->getSupportedCurrencies());
    }

    /**
     * Call webhook for transaction status update
     */
    public function callWebhook(OppwaTransaction $transaction): array
    {
        try {
            if (!$transaction->callback_url) {
                return [
                    'success' => false,
                    'error' => 'No callback URL provided for transaction'
                ];
            }

            $webhookData = $transaction->getWebhookPayload();

            Log::channel('oppwa')->info('Calling webhook', [
                'transaction_id' => $transaction->transaction_id,
                'callback_url' => $transaction->callback_url,
                'webhook_data' => $webhookData
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'User-Agent' => 'Adlef-OPPWA-Webhook/1.0',
                    'X-Webhook-Signature' => $this->generateWebhookSignature($webhookData),
                ])
                ->post($transaction->callback_url, $webhookData);

            if ($response->successful()) {
                Log::channel('oppwa')->info('Webhook called successfully', [
                    'transaction_id' => $transaction->transaction_id,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => true,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ];
            } else {
                Log::channel('oppwa')->error('Webhook call failed', [
                    'transaction_id' => $transaction->transaction_id,
                    'status_code' => $response->status(),
                    'response' => $response->body()
                ]);

                return [
                    'success' => false,
                    'error' => 'Webhook call failed with status: ' . $response->status(),
                    'response' => $response->body()
                ];
            }

        } catch (\Exception $e) {
            Log::channel('oppwa')->error('Webhook call exception', [
                'transaction_id' => $transaction->transaction_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate webhook signature for verification
     */
    private function generateWebhookSignature(array $data): string
    {
        $secret = config('services.oppwa.webhook_secret', 'default_secret');
        $payload = json_encode($data);
        return 'sha256=' . hash_hmac('sha256', $payload, $secret);
    }

    /**
     * Get configuration status
     */
    public function getConfigStatus(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'entity_id' => $this->entityId ? 'configured' : 'missing',
            'auth_bearer' => $this->authBearer ? 'configured' : 'missing',
            'sandbox' => $this->sandbox,
            'status' => ($this->entityId && $this->authBearer) ? 'ready' : 'incomplete',
        ];
    }

    /**
     * Analyze OPPWA response and return detailed status information
     */
    public function analyzeOppwaResponse(array $response): array
    {
        $resultCode = $response['result']['code'] ?? null;

        if (!$resultCode) {
            return [
                'status' => OppwaTransaction::STATUS_FAILED,
                'category' => 'failed',
                'description' => 'No result code provided',
                'requires_manual_review' => false,
                'is_successful' => false,
                'is_pending' => false,
                'is_failed' => true,
            ];
        }

        return [
            'status' => OppwaTransaction::determineStatusFromOppwaResponse($response),
            'category' => OppwaTransaction::getResultCodeCategory($resultCode),
            'description' => OppwaTransaction::getOppwaStatusDescription($resultCode),
            'requires_manual_review' => OppwaTransaction::requiresManualReview($resultCode),
            'is_successful' => OppwaTransaction::isSuccessfulResultCode($resultCode),
            'is_pending' => OppwaTransaction::isPendingResultCode($resultCode),
            'is_failed' => OppwaTransaction::isFailedResultCode($resultCode),
            'result_code' => $resultCode,
            'raw_description' => $response['result']['description'] ?? null,
        ];
    }
}
