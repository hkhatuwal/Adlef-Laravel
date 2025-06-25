<?php

namespace App\Services;

use App\Models\ApiClient;
use App\Models\PaymentTransaction;
use App\Contracts\PaymentGatewayFactory;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class ClientPaymentService
{
    protected PaymentGatewayFactory $gatewayFactory;
    protected PaymentService $paymentService;

    public function __construct(PaymentGatewayFactory $gatewayFactory, PaymentService $paymentService)
    {
        $this->gatewayFactory = $gatewayFactory;
        $this->paymentService = $paymentService;
    }

    /**
     * Create a payment for a client
     */
    public function createPayment(ApiClient $client, array $paymentData, string $gatewayName = 'payop'): array
    {
        // Validate client can make payment
        if (!$client->isCurrencyAllowed($paymentData['currency'])) {
            return [
                'success' => false,
                'message' => 'Currency not allowed for your account',
                'error_code' => 'CURRENCY_NOT_ALLOWED'
            ];
        }

        if (!$client->isWithinLimits($paymentData['amount'])) {
            return [
                'success' => false,
                'message' => 'Payment amount exceeds your limits',
                'error_code' => 'LIMIT_EXCEEDED'
            ];
        }

        try {
            // Create transaction record
            $transaction = $this->createTransactionRecord($client, $paymentData, $gatewayName);

            // Process payment with gateway
            $gateway = $this->gatewayFactory->create($gatewayName);
            
            // Prepare gateway payment data
            $gatewayPaymentData = $this->prepareGatewayPaymentData($paymentData, $transaction);
            
            $gatewayResponse = $gateway->processPayment($gatewayPaymentData);

            if ($gatewayResponse->isSuccessful()) {
                // Update transaction with gateway response
                $transaction->update([
                    'gateway_transaction_id' => $gatewayResponse->getTransactionId(),
                    'payment_url' => $gatewayResponse->paymentUrl ?? null,
                    'status' => PaymentTransaction::STATUS_PROCESSING,
                    'gateway_status' => $gatewayResponse->getStatus(),
                    'gateway_response' => [
                        'transaction_id' => $gatewayResponse->getTransactionId(),
                        'status' => $gatewayResponse->getStatus(),
                        'payment_url' => $gatewayResponse->paymentUrl ?? null,
                        'amount' => $gatewayResponse->getAmount(),
                        'currency' => $gatewayResponse->getCurrency(),
                    ]
                ]);

                // Update client usage
                $client->updateUsage($paymentData['amount']);

                return [
                    'success' => true,
                    'message' => 'Payment created successfully',
                    'data' => $transaction->toApiResponse()
                ];
            }

            // Handle gateway failure
            $transaction->updateStatus(
                PaymentTransaction::STATUS_FAILED,
                ['error' => $gatewayResponse->getMessage()],
                $gatewayResponse->getMessage()
            );

            return [
                'success' => false,
                'message' => $gatewayResponse->getMessage(),
                'error_code' => $gatewayResponse->getErrorCode() ?? 'GATEWAY_ERROR',
                'transaction_id' => $transaction->transaction_id
            ];

        } catch (Exception $e) {
            Log::error('Client payment creation failed', [
                'client_id' => $client->id,
                'error' => $e->getMessage(),
                'payment_data' => $paymentData
            ]);

            // Update transaction if it was created
            if (isset($transaction)) {
                $transaction->updateStatus(
                    PaymentTransaction::STATUS_FAILED,
                    null,
                    'Internal error: ' . $e->getMessage()
                );
            }

            return [
                'success' => false,
                'message' => 'Failed to create payment. Please try again.',
                'error_code' => 'INTERNAL_ERROR'
            ];
        }
    }

    /**
     * Create transaction record
     */
    protected function createTransactionRecord(ApiClient $client, array $paymentData, string $gatewayName): PaymentTransaction
    {
        return PaymentTransaction::create([
            'api_client_id' => $client->id,
            'transaction_id' => PaymentTransaction::generateTransactionId(),
            'gateway_name' => $gatewayName,
            'client_order_id' => $paymentData['order_id'] ?? null,
            'amount' => $paymentData['amount'],
            'currency' => strtoupper($paymentData['currency']),
            'description' => $paymentData['description'] ?? 'Payment via ' . ucfirst($gatewayName),
            'customer_email' => $paymentData['customer_email'] ?? null,
            'customer_name' => $paymentData['customer_name'] ?? null,
            'customer_phone' => $paymentData['customer_phone'] ?? null,
            'return_url' => $paymentData['return_url'] ?? null,
            'cancel_url' => $paymentData['cancel_url'] ?? null,
            'status' => PaymentTransaction::STATUS_PENDING,
            'metadata' => $paymentData['metadata'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Prepare payment data for gateway
     */
    protected function prepareGatewayPaymentData(array $paymentData, PaymentTransaction $transaction): array
    {
        return [
            'amount' => $paymentData['amount'],
            'currency' => strtoupper($paymentData['currency']),
            'description' => $paymentData['description'] ?? 'Payment via API',
            'customer_email' => $paymentData['customer_email'] ?? '',
            'customer_name' => $paymentData['customer_name'] ?? '',
            'customer_phone' => $paymentData['customer_phone'] ?? '',
            'order_id' => $transaction->transaction_id, // Use our transaction ID as order ID
            'language' => $paymentData['language'] ?? 'en',
            'return_url' => $paymentData['return_url'] ?? url('/payment/success'),
            'cancel_url' => $paymentData['cancel_url'] ?? url('/payment/cancel'),
        ];
    }

    /**
     * Handle webhook from gateway
     */
    public function handleGatewayWebhook(string $gatewayName, array $webhookData): void
    {
        try {
            // Find transaction by gateway transaction ID or our transaction ID
            $transaction = $this->findTransactionFromWebhook($gatewayName, $webhookData);

            if (!$transaction) {
                Log::warning('Transaction not found for webhook', [
                    'gateway' => $gatewayName,
                    'webhook_data' => $webhookData
                ]);
                return;
            }

            // Update transaction status based on webhook
            $this->updateTransactionFromWebhook($transaction, $webhookData);

            // Send webhook to client
            $this->sendClientWebhook($transaction);

        } catch (Exception $e) {
            Log::error('Failed to handle gateway webhook', [
                'gateway' => $gatewayName,
                'error' => $e->getMessage(),
                'webhook_data' => $webhookData
            ]);
        }
    }

    /**
     * Find transaction from webhook data
     */
    protected function findTransactionFromWebhook(string $gatewayName, array $webhookData): ?PaymentTransaction
    {
        $gatewayTransactionId = $webhookData['data']['id'] ?? $webhookData['id'] ?? null;
        $orderId = $webhookData['data']['order_id'] ?? $webhookData['order_id'] ?? null;

        if ($gatewayTransactionId) {
            return PaymentTransaction::where('gateway_transaction_id', $gatewayTransactionId)
                ->where('gateway_name', $gatewayName)
                ->first();
        }

        if ($orderId) {
            return PaymentTransaction::where('transaction_id', $orderId)
                ->where('gateway_name', $gatewayName)
                ->first();
        }

        return null;
    }

    /**
     * Update transaction from webhook
     */
    protected function updateTransactionFromWebhook(PaymentTransaction $transaction, array $webhookData): void
    {
        $eventType = $webhookData['type'] ?? $webhookData['event'] ?? 'unknown';
        $transactionData = $webhookData['data'] ?? $webhookData;

        $status = PaymentTransaction::STATUS_PENDING;
        
        switch ($eventType) {
            case 'invoice.paid':
            case 'payment.success':
                $status = PaymentTransaction::STATUS_COMPLETED;
                break;

            case 'invoice.failed':
            case 'payment.failed':
                $status = PaymentTransaction::STATUS_FAILED;
                break;

            case 'invoice.refunded':
            case 'refund.success':
                $status = PaymentTransaction::STATUS_REFUNDED;
                break;
        }

        $transaction->updateStatus($status, $transactionData);

        Log::info('Transaction updated from webhook', [
            'transaction_id' => $transaction->transaction_id,
            'old_status' => $transaction->getOriginal('status'),
            'new_status' => $status,
            'event_type' => $eventType
        ]);
    }

    /**
     * Send webhook to client
     */
    protected function sendClientWebhook(PaymentTransaction $transaction): void
    {
        $client = $transaction->apiClient;
        $webhookUrls = $client->getWebhookUrls();

        if (empty($webhookUrls)) {
            return;
        }

        $payload = json_encode($transaction->getWebhookPayload());
        $signature = $client->generateWebhookSignature($payload);

        foreach ($webhookUrls as $url) {
            try {
                $response = Http::timeout(30)
                    ->withHeaders([
                        'X-Signature' => $signature,
                        'X-Event-Type' => 'payment.' . $transaction->status,
                        'Content-Type' => 'application/json',
                    ])
                    ->post($url, $transaction->getWebhookPayload());

                $transaction->recordWebhookAttempt([
                    'url' => $url,
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'success' => $response->successful(),
                ]);

                Log::info('Client webhook sent', [
                    'transaction_id' => $transaction->transaction_id,
                    'client_id' => $client->id,
                    'webhook_url' => $url,
                    'status' => $response->status()
                ]);

            } catch (Exception $e) {
                $transaction->recordWebhookAttempt([
                    'url' => $url,
                    'error' => $e->getMessage(),
                    'success' => false,
                ]);

                Log::error('Failed to send client webhook', [
                    'transaction_id' => $transaction->transaction_id,
                    'client_id' => $client->id,
                    'webhook_url' => $url,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Get transaction by ID for client
     */
    public function getTransactionForClient(ApiClient $client, string $transactionId): ?PaymentTransaction
    {
        return $client->paymentTransactions()
            ->where('transaction_id', $transactionId)
            ->first();
    }

    /**
     * Get client transactions with pagination
     */
    public function getClientTransactions(ApiClient $client, array $filters = [], int $perPage = 15): array
    {
        $query = $client->paymentTransactions()->latest();

        // Apply filters
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['currency'])) {
            $query->where('currency', strtoupper($filters['currency']));
        }

        if (!empty($filters['from_date'])) {
            $query->whereDate('created_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('created_at', '<=', $filters['to_date']);
        }

        $transactions = $query->paginate($perPage);

        return [
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
            ]
        ];
    }
} 