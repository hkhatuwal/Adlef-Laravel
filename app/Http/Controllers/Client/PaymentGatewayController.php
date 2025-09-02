<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
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
