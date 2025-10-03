<?php

namespace App\Services;

use App\Models\ApiClient;
use App\Models\PaymentTransaction;
use App\Models\UserPaymentSettings;
use App\Models\PaymentGatewayWallet;
use App\Models\Currency;
use App\Contracts\PaymentGatewayFactory;
use App\Contracts\WebhookData;
use App\Services\PaymentGateway\NgeniusPaymentGateway;
use App\Services\PaymentService;
use App\Utils\CurrencyConverter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Exception;

class ClientPaymentService
{
    protected PaymentGatewayFactory $gatewayFactory;
    protected PaymentService $paymentService;
    protected CurrencyConverter $currencyConverter;

    public function __construct(PaymentGatewayFactory $gatewayFactory, PaymentService $paymentService, CurrencyConverter $currencyConverter)
    {
        $this->gatewayFactory = $gatewayFactory;
        $this->paymentService = $paymentService;
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * Select an available gateway for a category (e.g., card, crypto)
     * based on user's provider allowances and remaining limits.
     */
    public function selectGatewayForCategory(ApiClient $client, string $category, float $amount): ?string
    {
        $providersByCategory = config('constants.internal_payment_providers');
        $providers = $providersByCategory[$category] ?? [];

        if (empty($providers)) {
            return null;
        }

        // Ensure payment settings exist and are active


        $paymentSettings=$this->getUserPaymentSettings($client->user);
        if (!$paymentSettings->is_active) {
            return null;
        }


        foreach ($providers as $provider=> $configs) {
            if (!$paymentSettings->isProviderAllowed($provider)) {
                continue;
            }

            if ($configs['max_limit'] < $amount || $amount < $configs['min_limit']) {

                continue;
            }

            $limitCheck = $this->checkUserPaymentLimits($client, $amount, $provider);


            if ($limitCheck['success'] ?? false) {
                return $provider;
            }


        }

        return null;
    }



    public function getAllowedPaymentMethods(ApiClient $client,$amount): array
    {
        $allProviders = config('constants.internal_payment_providers');
        $allowedProviders = [];
        $paymentSettings=$this->getUserPaymentSettings($client->user);
        if (!$paymentSettings->is_active) {
            return [];
        }


        foreach ($allProviders as $category=>$providers) {
            foreach ($providers as $provider=> $configs) {
                if (!$paymentSettings->isProviderAllowed($provider)) {
                    continue;
                }

                if ($configs['max_limit'] < $amount || $amount< $configs['min_limit']) {
                    continue;
                }

                $limitCheck = $this->checkUserPaymentLimits($client, $amount, $provider);
                if ($limitCheck['success'] ?? false) {
                    $allowedProviders[$category][] = $provider;
                }
            }
        }

        return $allowedProviders;

    }



    private function getUserPaymentSettings($user)
    {
        $paymentSettings = $user->paymentSettings;
        if (!$paymentSettings) {
            $paymentSettings = UserPaymentSettings::create([
                'user_id' =>$user->id,
                ...UserPaymentSettings::getDefaultSettings()
            ]);
        }

        return $paymentSettings;

    }


    public function createPayment(ApiClient $client, array $paymentData, string $gatewayName = 'payop',PaymentTransaction $paymentTransaction=null): array
    {
        // Validate client can make payment
        if (!$client->isCurrencyAllowed($paymentData['currency'])) {
            return [
                'success' => false,
                'message' => 'Currency not allowed for your account',
                'error_code' => 'CURRENCY_NOT_ALLOWED'
            ];
        }

        // Check user payment settings and provider-specific limits
        $limitCheck = $this->checkUserPaymentLimits($client, $paymentData['amount'], $gatewayName);
        if (!$limitCheck['success']) {
            throw new Exception("Please contact admin. Limit exceeded");
        }

        try {
            // Create transaction record
            $transaction =$paymentTransaction ?? $this->createTransactionRecord($client, $paymentData, $gatewayName);

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
                    'status' => PaymentTransaction::STATUS_PENDING,
                    'amount' =>$gatewayResponse->getAmount(),
                    'currency' =>$gatewayResponse->getCurrency(),
                    'gateway_status' => $gatewayResponse->getStatus(),
                    'gateway_response' => [
                        'transaction_id' => $gatewayResponse->getTransactionId(),
                        'status' => $gatewayResponse->getStatus(),
                        'payment_url' => $gatewayResponse->paymentUrl ?? null,
                        'amount' => $gatewayResponse->getAmount(),
                        'currency' => $gatewayResponse->getCurrency(),
                    ]
                ]);

                // Note: Client usage is now tracked per-provider in the payment settings
                // The old global usage tracking is replaced by provider-specific limits

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
     * Check user payment limits for specific provider
     */
    protected function checkUserPaymentLimits(ApiClient $client, float $amount, string $gatewayName): array
    {
        // Get user's payment settings
        $paymentSettings = $client->user->paymentSettings;

        // If no payment settings exist, create default ones
        if (!$paymentSettings) {
            $paymentSettings = UserPaymentSettings::create([
                'user_id' => $client->user_id,
                ...UserPaymentSettings::getDefaultSettings()
            ]);
        }

        // Check if payment settings are active
        if (!$paymentSettings->is_active) {
            return [
                'success' => false,
                'message' => 'Payment processing is disabled for your account',
                'error_code' => 'PAYMENT_DISABLED'
            ];
        }

        // Check if provider is allowed
        if (!$paymentSettings->isProviderAllowed($gatewayName)) {
            return [
                'success' => false,
                'message' => "Payment provider '{$gatewayName}' is not allowed for your account",
                'error_code' => 'PROVIDER_NOT_ALLOWED'
            ];
        }

        // Get provider-specific limits
        $providerLimits = $paymentSettings->getProviderLimits($gatewayName);
        $dailyLimit = $providerLimits['daily_limit'] ?? 0;
        $monthlyLimit = $providerLimits['monthly_limit'] ?? 0;

        // Check if limits are set (0 means no limit)
        if ($dailyLimit > 0 || $monthlyLimit > 0) {
            // Get current usage for this provider
            $currentUsage = $this->getCurrentProviderUsage($client, $gatewayName);

            // Check daily limit
            if ($dailyLimit > 0 && ($currentUsage['daily_used'] + $amount) > $dailyLimit) {
                return [
                    'success' => false,
                    'message' => "Payment amount exceeds daily limit for {$gatewayName}. Daily limit: {$dailyLimit}, Used: {$currentUsage['daily_used']}, Requested: {$amount}",
                    'error_code' => 'DAILY_LIMIT_EXCEEDED',
                    'limit_info' => [
                        'provider' => $gatewayName,
                        'limit_type' => 'daily',
                        'limit_amount' => $dailyLimit,
                        'used_amount' => $currentUsage['daily_used'],
                        'requested_amount' => $amount
                    ]
                ];
            }

            // Check monthly limit
            if ($monthlyLimit > 0 && ($currentUsage['monthly_used'] + $amount) > $monthlyLimit) {
                return [
                    'success' => false,
                    'message' => "Payment amount exceeds monthly limit for {$gatewayName}. Monthly limit: {$monthlyLimit}, Used: {$currentUsage['monthly_used']}, Requested: {$amount}",
                    'error_code' => 'MONTHLY_LIMIT_EXCEEDED',
                    'limit_info' => [
                        'provider' => $gatewayName,
                        'limit_type' => 'monthly',
                        'limit_amount' => $monthlyLimit,
                        'used_amount' => $currentUsage['monthly_used'],
                        'requested_amount' => $amount
                    ]
                ];
            }
        }

        return ['success' => true];
    }

    /**
     * Get current usage for a specific provider
     */
    protected function getCurrentProviderUsage(ApiClient $client, string $gatewayName): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        // Get all user's API clients
        $userClientIds = $client->user->apiClients()->pluck('id')->toArray();

        // Calculate daily usage for this provider
        $dailyUsed = PaymentTransaction::whereIn('api_client_id', $userClientIds)
            ->where('gateway_name', $gatewayName)
            ->where('status', PaymentTransaction::STATUS_COMPLETED)
            ->where('created_at', '>=', $today)
            ->sum('amount');

        // Calculate monthly usage for this provider
        $monthlyUsed = PaymentTransaction::whereIn('api_client_id', $userClientIds)
            ->where('gateway_name', $gatewayName)
            ->where('status', PaymentTransaction::STATUS_COMPLETED)
            ->where('created_at', '>=', $thisMonth)
            ->sum('amount');

        return [
            'daily_used' => $dailyUsed,
            'monthly_used' => $monthlyUsed
        ];
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
            // Create gateway instance once
            $gateway = $this->gatewayFactory->create($gatewayName);

            // Parse webhook data once
            $parsedData = $gateway->parseWebhookData($webhookData);

            // Find transaction using parsed data
            $transaction = $this->findTransactionFromParsedData($gatewayName, $parsedData);

            if (!$transaction) {
                Log::warning('Transaction not found for webhook', [
                    'gateway' => $gatewayName,
                    'transaction_id' => $parsedData->getTransactionId(),
                    'parsed_data' => $parsedData->toArray(),
                    'webhook_data' => $webhookData
                ]);
                return;
            }

            if ($transaction->status== PaymentTransaction::STATUS_COMPLETED) {
                Log::warning("Transaction already completed");
                return ;
            }

            // Update transaction status using parsed data
            $this->updateTransactionFromParsedData($transaction, $parsedData);

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
     * Find transaction from parsed webhook data
     * @throws Exception
     */
    protected function findTransactionFromParsedData(string $gatewayName, WebhookData $parsedData): ?PaymentTransaction
    {
        return PaymentTransaction::where('transaction_id', $parsedData->getTransactionId())
            ->where('gateway_name', $gatewayName)
            ->first();
    }

    /**
     * Update transaction from parsed webhook data
     */
    protected function updateTransactionFromParsedData(PaymentTransaction $transaction, WebhookData $parsedData): void
    {
        // Map the parsed status to our transaction status constants
        $status = match ($parsedData->getStatus()) {
            'completed' => PaymentTransaction::STATUS_COMPLETED,
            'failed' => PaymentTransaction::STATUS_FAILED,
            'cancelled' => PaymentTransaction::STATUS_CANCELLED,
            default => PaymentTransaction::STATUS_PENDING,
        };

        // Update transaction with parsed data
        $updateData = [
            'status' => $status,
            'failure_reason'=>$parsedData->failureReason,
            'gateway_status' => $parsedData->getStatus(),
            'gateway_response' => $parsedData->toArray()
        ];

        // Update amount and currency if provided in webhook
        if ($parsedData->getAmount() !== null) {
            $updateData['amount'] = $parsedData->getAmount();
        }
        if ($parsedData->getCurrency() !== null) {
            $updateData['currency'] = $parsedData->getCurrency();
        }
        if ($parsedData->getGatewayTransactionId() !== null) {
            $updateData['gateway_transaction_id'] = $parsedData->getGatewayTransactionId();
        }

        $transaction->update($updateData);

        // Update payment method details from webhook data
        $paymentMethodData = $this->extractPaymentMethodDataFromWebhook($parsedData);
        if (!empty($paymentMethodData)) {
            $transaction->updatePaymentMethodDetails($paymentMethodData);
        }

        // Handle wallet update for completed transactions
        if ($status === PaymentTransaction::STATUS_COMPLETED) {
            $this->updateUserWalletFromTransaction($transaction);
        }

        Log::info('Transaction updated from webhook', [
            'transaction_id' => $transaction->transaction_id,
            'old_status' => $transaction->getOriginal('status'),
            'new_status' => $status,
            'gateway' => $transaction->gateway_name,
            'event_type' => $parsedData->getEventType() ?? 'payment',
            'payment_method_type' => $paymentMethodData['payment_method_type'] ?? null,
            'payment_method_updated' => !empty($paymentMethodData)
        ]);
    }

    /**
     * Extract payment method data from webhook data
     */
    protected function extractPaymentMethodDataFromWebhook(WebhookData $parsedData): array
    {
        // Use the helper method from WebhookData to get payment method data
        // formatted for the PaymentTransaction model
        return $parsedData->getPaymentMethodDataForTransaction();
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
     * Get paginated transactions for a user's API clients
     */
    public function getTransactionsForUser($user, array $filters = [], int $perPage = 15)
    {
        // Get user's API client IDs
        $clientIds = ApiClient::where('user_id', $user->id)
            ->pluck('id')
            ->toArray();

        if (empty($clientIds)) {
            return PaymentTransaction::whereRaw('1 = 0')->paginate($perPage); // Empty result
        }

        $query = PaymentTransaction::query()
            ->whereIn('api_client_id', $clientIds)
            ->with(['apiClient:id,name']) // Eager load client info
            ->latest();

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

        return $query->paginate($perPage);
    }

    /**
     * Get transaction statistics for a user's API clients
     */
    public function getTransactionStatsForUser($user): array
    {
        $clientIds = ApiClient::where('user_id', $user->id)
            ->pluck('id')
            ->toArray();

        if (empty($clientIds)) {
            return [
                'total_transactions' => 0,
                'successful_transactions' => 0,
                'failed_transactions' => 0,
                'pending_transactions' => 0,
                'total_amount' => 0,
                'wallet_balance' => 0,
                'currencies' => []
            ];
        }

        // Get aggregated stats in a single query
        $stats = PaymentTransaction::whereIn('api_client_id', $clientIds)
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as successful_transactions,
                SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed_transactions,
                SUM(CASE WHEN status IN ("pending", "processing") THEN 1 ELSE 0 END) as pending_transactions,
                SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount
            ')
            ->first();

        // Get unique currencies
        $currencies = PaymentTransaction::whereIn('api_client_id', $clientIds)
            ->select('currency')
            ->distinct()
            ->pluck('currency')
            ->toArray();

        // Get wallet balance from PaymentGatewayWallet
        $walletBalance = PaymentGatewayWallet::getTotalBalanceForUser($user->id);

        return [
            'total_transactions' => $stats->total_transactions ?? 0,
            'successful_transactions' => $stats->successful_transactions ?? 0,
            'failed_transactions' => $stats->failed_transactions ?? 0,
            'pending_transactions' => $stats->pending_transactions ?? 0,
            'total_amount' => $walletBalance, // Use wallet balance instead of transaction sum
            'wallet_balance' => $walletBalance,
            'currencies' => $currencies
        ];
    }

    /**
     * Get user's API clients
     */
    public function getApiClientsForUser($user)
    {
        return ApiClient::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get current usage statistics for all providers for a user
     */
    public function getUserProviderUsageStats($user): array
    {
        $userClientIds = $user->apiClients()->pluck('id')->toArray();

        if (empty($userClientIds)) {
            return [];
        }

        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $availableProviders = config('constants.internal_payment_providers');

        $stats = [];

        foreach ($availableProviders as $category => $providers) {
            foreach ($providers as  $provider=>$config) {
                $dailyUsed = PaymentTransaction::whereIn('api_client_id', $userClientIds)
                    ->where('gateway_name', $provider)
                    ->where('status', PaymentTransaction::STATUS_COMPLETED)
                    ->where('created_at', '>=', $today)
                    ->sum('amount');

                $monthlyUsed = PaymentTransaction::whereIn('api_client_id', $userClientIds)
                    ->where('gateway_name', $provider)
                    ->where('status', PaymentTransaction::STATUS_COMPLETED)
                    ->where('created_at', '>=', $thisMonth)
                    ->sum('amount');

                $stats[$provider] = [
                    'category' => $category,
                    'daily_used' => $dailyUsed,
                    'monthly_used' => $monthlyUsed,
                    'daily_transactions' => PaymentTransaction::whereIn('api_client_id', $userClientIds)
                        ->where('gateway_name', $provider)
                        ->where('status', PaymentTransaction::STATUS_COMPLETED)
                        ->where('created_at', '>=', $today)
                        ->count(),
                    'monthly_transactions' => PaymentTransaction::whereIn('api_client_id', $userClientIds)
                        ->where('gateway_name', $provider)
                        ->where('status', PaymentTransaction::STATUS_COMPLETED)
                        ->where('created_at', '>=', $thisMonth)
                        ->count(),
                ];
            }
        }

        return $stats;
    }

    /**
     * Update user's payment gateway wallet when transaction is completed
     */
    protected function updateUserWalletFromTransaction(PaymentTransaction $transaction): void
    {
        try {
            $user = $transaction->apiClient->user;
            $amount = $transaction->amount;
            $currency = $transaction->currency;
            $gatewayName = $transaction->gateway_name;

            Log::info("Converting ". $amount ." From ". $currency);

            // Convert amount to USD if currency is not USD
            $amountInUSD = $this->convertAmountToUSD($amount, $currency);

            // Get or create wallet for this user and gateway
            $wallet = PaymentGatewayWallet::getOrCreateWallet($user->id, $gatewayName);

            // Add amount to wallet
            $wallet->addBalance($amountInUSD);

            Log::info('Payment gateway wallet updated', [
                'user_id' => $user->id,
                'gateway_name' => $gatewayName,
                'original_amount' => $amount,
                'original_currency' => $currency,
                'amount_in_usd' => $amountInUSD,
                'new_balance' => $wallet->balance_usd,
                'transaction_id' => $transaction->transaction_id
            ]);

        } catch (Exception $e) {
            Log::error('Failed to update payment gateway wallet', [
                'transaction_id' => $transaction->transaction_id,
                'user_id' => $transaction->apiClient->user_id,
                'gateway_name' => $transaction->gateway_name,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Convert amount to USD using CurrencyConverter
     */
    protected function convertAmountToUSD(float $amount, string $currency): float
    {
        // If already USD, return as is
        if (strtoupper($currency) === 'USD') {
            return $amount;
        }

        try {
            // Find the currency model
            $currencyModel = Currency::where('symbol', strtoupper($currency))->first();

            if (!$currencyModel) {
                Log::warning('Currency not found for conversion', [
                    'currency' => $currency,
                    'amount' => $amount
                ]);
                return $amount; // Return original amount if currency not found
            }

            // Convert to USD using CurrencyConverter
            return $this->currencyConverter->convertToUSD($amount, $currencyModel);

        } catch (Exception $e) {
            Log::error('Failed to convert currency to USD', [
                'currency' => $currency,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            return $amount; // Return original amount on error
        }
    }

}
