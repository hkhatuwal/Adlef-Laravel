<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\PaymentGatewayWallet;
use App\Models\Setting;
use App\Models\Settlement;
use App\Models\UserPaymentSettings;
use App\Models\AssetAccount;
use App\Models\Currency;
use App\Services\ClientPaymentService;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentGatewayController extends Controller
{
    protected ClientPaymentService $paymentService;

    public function __construct(ClientPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display the payment gateway management page
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get pagination parameters and filters
        $perPage = 15;
        $filters = [
            'status' => $request->get('status'),
            'currency' => $request->get('currency'),
            'from_date' => $request->get('from_date'),
            'to_date' => $request->get('to_date'),
        ];

        // Use service methods to get data
        $transactions = $this->paymentService->getTransactionsForUser($user, $filters, $perPage);
        $apiClients = $this->paymentService->getApiClientsForUser($user);
        $stats = $this->paymentService->getTransactionStatsForUser($user);

        return view('client.payment-gateway.index', compact(
            'transactions',
            'apiClients',
            'stats',
            'filters'
        ));
    }



    /**
     * Show transaction details
     */
    public function showTransaction(Request $request, $transactionId)
    {
        $user = Auth::user();
        $apiClients = $this->paymentService->getApiClientsForUser($user);
        $transaction = null;

        foreach ($apiClients as $client) {
            $transaction = $this->paymentService->getTransactionForClient($client, $transactionId);
            if ($transaction) {
                break;
            }
        }

        if (!$transaction) {
            abort(404, 'Transaction not found');
        }

        return view('client.payment-gateway.transaction-details', compact('transaction'));
    }

    /**
     * Get API key details for editing
     */
    public function getApiKey(ApiClient $apiClient)
    {
        $user = Auth::user();

        if ($apiClient->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'client' => $apiClient
        ]);
    }

    /**
     * Store a new API key
     */
    public function storeApiKey(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'is_sandbox' => 'required|boolean',
            'allowed_ips' => 'nullable|string',
            'webhook_urls' => 'nullable|string',
            'allowed_currencies' => 'nullable|array',
            'allowed_currencies.*' => 'string|in:USD,EUR,GBP,CAD,AUD,JPY,CHF,CNY,INR',
        ]);

        // Generate API credentials
        $credentials = ApiClient::generateCredentials();

        // Process allowed IPs
        $allowedIps = [];
        if (!empty($validatedData['allowed_ips'])) {
            $allowedIps = array_filter(
                array_map('trim', explode("\n", $validatedData['allowed_ips'])),
                'strlen'
            );
        }

        // Process webhook URLs
        $webhookUrls = [];
        if (!empty($validatedData['webhook_urls'])) {
            $webhookUrls = array_filter(
                array_map('trim', explode("\n", $validatedData['webhook_urls'])),
                'strlen'
            );
        }

        $apiClient = ApiClient::create([
            'user_id' => $user->id,
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'company_name' => $validatedData['company_name'],
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
            'is_active' => true,
            'is_sandbox' => $validatedData['is_sandbox'],
            'allowed_ips' => $allowedIps,
            'webhook_urls' => $webhookUrls,
            'allowed_currencies' => $validatedData['allowed_currencies'] ?? [],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'API key created successfully',
            'api_client' => $apiClient
        ]);
    }

    /**
     * Update API key
     */
    public function updateApiKey(Request $request, ApiClient $apiClient)
    {
        $user = Auth::user();

        if ($apiClient->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Debug: Log all request data
        \Log::info('Update API Key Request Data:', [
            'all' => $request->all(),
            'input' => $request->input(),
            'raw' => $request->getContent(),
            'method' => $request->method(),
            'files' => $request->allFiles(),
            'headers' => $request->headers->all()
        ]);



        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'is_sandbox' => 'required|boolean',
            'allowed_ips' => 'nullable|string',
            'webhook_urls' => 'nullable|string',
            'allowed_currencies' => 'nullable|array',
            'allowed_currencies.*' => 'string|in:USD,EUR,GBP,CAD,AUD,JPY,CHF,CNY,INR',
            'daily_limit' => 'nullable|numeric|min:0',
            'monthly_limit' => 'nullable|numeric|min:0',
        ]);

        // Process allowed IPs
        $allowedIps = [];
        if (!empty($validatedData['allowed_ips'])) {
            $allowedIps = array_filter(
                array_map('trim', explode("\n", $validatedData['allowed_ips'])),
                'strlen'
            );
        }

        // Process webhook URLs
        $webhookUrls = [];
        if (!empty($validatedData['webhook_urls'])) {
            $webhookUrls = array_filter(
                array_map('trim', explode("\n", $validatedData['webhook_urls'])),
                'strlen'
            );
        }

        $apiClient->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'company_name' => $validatedData['company_name'],
            'is_sandbox' => $validatedData['is_sandbox'],
            'allowed_ips' => $allowedIps,
            'webhook_urls' => $webhookUrls,
            'allowed_currencies' => $validatedData['allowed_currencies'] ?? [],
            'daily_limit' => $validatedData['daily_limit'],
            'monthly_limit' => $validatedData['monthly_limit'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'API key updated successfully',
            'api_client' => $apiClient->fresh()
        ]);
    }

    /**
     * Regenerate API key
     */
    public function regenerateApiKey(ApiClient $apiClient)
    {
        $user = Auth::user();

        if ($apiClient->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $credentials = ApiClient::generateCredentials();

        $apiClient->update([
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'API key regenerated successfully'
        ]);
    }

    /**
     * Toggle API client status
     */
    public function toggleApiClientStatus(Request $request, ApiClient $apiClient)
    {
        $user = Auth::user();

        if ($apiClient->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $apiClient->update([
            'is_active' => $validated['is_active']
        ]);

        return response()->json([
            'success' => true,
            'message' => $validated['is_active']
                ? 'API key activated successfully'
                : 'API key deactivated successfully'
        ]);
    }

    /**
     * Delete API client
     */
    public function deleteApiClient(ApiClient $apiClient)
    {
        $user = Auth::user();

        if ($apiClient->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $apiClient->delete();

        return response()->json([
            'success' => true,
            'message' => 'API key deleted successfully'
        ]);
    }

    /**
     * Display the settlement management page
     */
    public function settlement()
    {
        $user = Auth::user();

        // Get user's payment settings for fee calculations
        $paymentSettings = $user->paymentSettings ?? UserPaymentSettings::create([
            'user_id' => $user->id,
            ...UserPaymentSettings::getDefaultSettings()
        ]);

        // Get wallet information
        $wallets = PaymentGatewayWallet::getWalletsForUser($user->id);
        $totalBalance = PaymentGatewayWallet::getTotalBalanceForUser($user->id);

        // Get settlement history
        $settlements = Settlement::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get successful payment transactions for the user's API clients
        $clientIds = $user->apiClients()->pluck('id')->toArray();
        $successfulPayments = collect();

        if (!empty($clientIds)) {
            $successfulPayments = PaymentTransaction::whereIn('api_client_id', $clientIds)
                ->where('status', 'completed')
                ->with(['apiClient:id,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'payments_page');
        }

        // Get settlement statistics
        $settlementStats = [
            'total_requested' => Settlement::where('user_id', $user->id)->sum('amount'),
            'total_fees' => Settlement::where('user_id', $user->id)->sum('fee_amount'),
            'total_net' => Settlement::where('user_id', $user->id)->sum('net_amount'),
            'pending_count' => Settlement::where('user_id', $user->id)->where('status', Settlement::STATUS_PENDING)->count(),
            'completed_count' => Settlement::where('user_id', $user->id)->where('status', Settlement::STATUS_COMPLETED)->count(),
        ];

        return view('client.payment-gateway.settlement', compact(
            'wallets',
            'totalBalance',
            'paymentSettings',
            'settlements',
            'settlementStats',
            'successfulPayments'
        ));
    }

    /**
     * Process settlement request for user's payment gateway wallets
     */
    public function processSettlement(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'settlement_amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            // Get user's payment settings
            $paymentSettings = $user->paymentSettings;
            if (!$paymentSettings) {
                $paymentSettings = UserPaymentSettings::create([
                    'user_id' => $user->id,
                    ...UserPaymentSettings::getDefaultSettings()
                ]);
            }

            // Get user's total wallet balance
            $totalBalance = PaymentGatewayWallet::getTotalBalanceForUser($user->id);

            if ($totalBalance <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No funds available for settlement'
                ], 400);
            }

            // Check if requested amount is valid
            $requestedAmount = $validated['settlement_amount'];
            if ($requestedAmount > $totalBalance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Requested amount exceeds available balance'
                ], 400);
            }

            // Calculate fees
            $feeAmount = $paymentSettings->calculateSettlementFee($requestedAmount);
            $netAmount = $requestedAmount - $feeAmount;

            // Create settlement record
            $settlement = Settlement::createRequest([
                'user_id' => $user->id,
                'amount' => $requestedAmount,
                'fee_amount' => $feeAmount,
                'net_amount' => $netAmount,
                'notes' => $validated['notes'] ?? null,
            ]);

            $gatewayWallets=PaymentGatewayWallet::getWalletsForUser(auth()->id());
            $totalCost=0;
            foreach ($gatewayWallets as $wallet) {
                if ($wallet->balance_usd>=$requestedAmount) {
                    $totalCost+=$this->getSettlementWalletCost($wallet,$requestedAmount);
                    $wallet->update(['balance_usd' => $wallet->balance_usd - $requestedAmount]);
                }
                else{
                    $requestedAmount -= $wallet->balance_usd;
                    $totalCost+=$this->getSettlementWalletCost($wallet,$wallet->balance_usd);
                    $wallet->update(['balance_usd' => 0]);
                }
            }



            $usdCurrency=Currency::query()->where('symbol','USD')->first();
            $usdAssetAccount=AssetAccount::where('currency_id',$usdCurrency->id)->where("user_id",$user->id)->first();
            $usdAssetAccount->update([
                'balance' => $usdAssetAccount->balance + $netAmount,
            ]);
            $settlement->update(['cost' => $totalCost,'status' => Settlement::STATUS_COMPLETED]);



            Log::info('Settlement request created', [
                'user_id' => $user->id,
                'settlement_id' => $settlement->id,
                'reference_id' => $settlement->reference_id,
                'requested_amount' => $requestedAmount,
                'fee_amount' => $feeAmount,
                'net_amount' => $netAmount,
                'total_balance' => $totalBalance,
                'notes' => $validated['notes'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Settlement request submitted successfully. You will be contacted within 24 hours.',
                'data' => [
                    'settlement_id' => $settlement->id,
                    'reference_id' => $settlement->reference_id,
                    'requested_amount' => $requestedAmount,
                    'fee_amount' => $feeAmount,
                    'net_amount' => $netAmount,
                    'total_balance' => $totalBalance,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Settlement request failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process settlement request. Please try again.'
            ], 500);
        }
    }

    private function getSettlementWalletCost(PaymentGatewayWallet $wallet,$amount)
    {
       $costType= Setting::get($wallet->gateway_name.'_cost_type');
       if(!isset($costType)){
           return 0;
       }
       if($costType=='fixed'){
           return Setting::get($wallet->gateway_name.'_cost_fixed');
       }

       $percentageValue=Setting::get($wallet->gateway_name.'_cost_percentage');
       return  $amount*($percentageValue/100);

    }
    /**
     * Get user's wallet information
     */
    public function getWalletInfo()
    {
        $user = Auth::user();

        $wallets = PaymentGatewayWallet::getWalletsForUser($user->id);
        $totalBalance = PaymentGatewayWallet::getTotalBalanceForUser($user->id);

        return response()->json([
            'success' => true,
            'data' => [
                'wallets' => $wallets,
                'total_balance' => $totalBalance
            ]
        ]);
    }
}
