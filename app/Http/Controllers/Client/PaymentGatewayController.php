<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\ClientPaymentService;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        // Get user's API clients for the transactions
        $apiClients = ApiClient::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        // Get transactions for all user's API clients
        $transactions = collect();
        $totalTransactions = 0;
        
        foreach ($apiClients as $client) {
            $clientTransactions = $this->paymentService->getClientTransactions($client, [
                'status' => $request->get('status'),
                'currency' => $request->get('currency'),
                'from_date' => $request->get('from_date'),
                'to_date' => $request->get('to_date'),
            ], 50);
            
            $transactions = $transactions->merge($clientTransactions['data']);
            $totalTransactions += $clientTransactions['pagination']['total'];
        }
        
        // Sort transactions by created_at desc
        $transactions = $transactions->sortByDesc('created_at');
        
        // Get summary statistics
        $stats = $this->getTransactionStats($apiClients);
        
        return view('client.payment-gateway.index', compact(
            'transactions', 
            'apiClients', 
            'stats', 
            'totalTransactions'
        ));
    }

    /**
     * Get transaction statistics
     */
    private function getTransactionStats($apiClients)
    {
        $stats = [
            'total_transactions' => 0,
            'successful_transactions' => 0,
            'failed_transactions' => 0,
            'pending_transactions' => 0,
            'total_amount' => 0,
            'currencies' => []
        ];

        foreach ($apiClients as $client) {
            $clientStats = $client->paymentTransactions()
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as successful,
                    SUM(CASE WHEN status = "failed" THEN 1 ELSE 0 END) as failed,
                    SUM(CASE WHEN status IN ("pending", "processing") THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = "completed" THEN amount ELSE 0 END) as total_amount
                ')
                ->first();

            $stats['total_transactions'] += $clientStats->total ?? 0;
            $stats['successful_transactions'] += $clientStats->successful ?? 0;
            $stats['failed_transactions'] += $clientStats->failed ?? 0;
            $stats['pending_transactions'] += $clientStats->pending ?? 0;
            $stats['total_amount'] += $clientStats->total_amount ?? 0;

            // Get currencies
            $currencies = $client->paymentTransactions()
                ->select('currency')
                ->distinct()
                ->pluck('currency')
                ->toArray();
            
            $stats['currencies'] = array_unique(array_merge($stats['currencies'], $currencies));
        }

        return $stats;
    }

    /**
     * Show transaction details
     */
    public function showTransaction(Request $request, $transactionId)
    {
        $user = Auth::user();
        $transaction = null;
        
        // Find transaction in user's API clients
        $apiClients = ApiClient::where('user_id', $user->id)->get();
        
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
     * Store a new API key
     */
    public function storeApiKey(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'company_name' => 'nullable|string|max:255',
            'is_sandbox' => 'required|boolean',
            'daily_limit' => 'nullable|numeric|min:0',
            'monthly_limit' => 'nullable|numeric|min:0',
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
            'daily_limit' => $validatedData['daily_limit'],
            'monthly_limit' => $validatedData['monthly_limit'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'API key created successfully',
            'api_client' => $apiClient
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
} 