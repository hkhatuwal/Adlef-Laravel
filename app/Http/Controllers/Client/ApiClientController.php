<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiClientController extends Controller
{
    /**
     * Display a listing of the user's API clients
     */
    public function index(): View
    {
        $user = Auth::user();
        $apiClients = $user->apiClients()->latest()->paginate(10);
        
        return view('client.api-clients.index', compact('apiClients'));
    }

    /**
     * Show the form for creating a new API client
     */
    public function create(): View
    {
        return view('client.api-clients.create');
    }

    /**
     * Store a newly created API client
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'webhook_urls' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = ApiClient::generateCredentials();
        $user = Auth::user();

        $client = ApiClient::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $user->email,
            'company_name' => $request->company_name,
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
            'is_sandbox' => true, // Default to sandbox for new clients
            'webhook_urls' => $request->webhook_urls ? 
                json_encode(array_map('trim', explode(',', $request->webhook_urls))) : null,
        ]);

        return redirect()->route('client.api-clients.show', $client)
            ->with('success', 'API Client created successfully')
            ->with('new_credentials', $credentials);
    }

    /**
     * Display the specified API client
     */
    public function show(ApiClient $apiClient): View
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $apiClient->load('paymentTransactions');
        $recentTransactions = $apiClient->paymentTransactions()->latest()->limit(10)->get();
        
        return view('client.api-clients.show', compact('apiClient', 'recentTransactions'));
    }

    /**
     * Show the form for editing the specified API client
     */
    public function edit(ApiClient $apiClient): View
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('client.api-clients.edit', compact('apiClient'));
    }

    /**
     * Update the specified API client
     */
    public function update(Request $request, ApiClient $apiClient): RedirectResponse
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'webhook_urls' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $apiClient->update([
            'name' => $request->name,
            'company_name' => $request->company_name,
            'webhook_urls' => $request->webhook_urls ? 
                json_encode(array_map('trim', explode(',', $request->webhook_urls))) : null,
        ]);

        return redirect()->route('client.api-clients.show', $apiClient)
            ->with('success', 'API Client updated successfully');
    }

    /**
     * Remove the specified API client
     */
    public function destroy(ApiClient $apiClient): RedirectResponse
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $apiClient->delete();

        return redirect()->route('client.api-clients.index')
            ->with('success', 'API Client deleted successfully');
    }

    /**
     * Generate new API credentials
     */
    public function regenerateCredentials(ApiClient $apiClient): RedirectResponse
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $credentials = ApiClient::generateCredentials();
        
        $apiClient->update([
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
        ]);

        return redirect()->route('client.api-clients.show', $apiClient)
            ->with('success', 'API credentials regenerated successfully')
            ->with('new_credentials', $credentials);
    }

    /**
     * Toggle client sandbox mode (clients can only switch to sandbox, not production)
     */
    public function toggleSandbox(ApiClient $apiClient): RedirectResponse
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow switching to sandbox mode (production mode requires admin approval)
        if (!$apiClient->is_sandbox) {
            return redirect()->back()
                ->with('error', 'Cannot switch to production mode. Please contact support.');
        }

        $apiClient->update(['is_sandbox' => false]);

        return redirect()->route('client.api-clients.show', $apiClient)
            ->with('success', 'Switched to production mode successfully');
    }

    /**
     * Get API client statistics
     */
    public function statistics(ApiClient $apiClient): View
    {
        // Ensure the client belongs to the authenticated user
        if ($apiClient->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $stats = [
            'total_transactions' => $apiClient->paymentTransactions()->count(),
            'successful_transactions' => $apiClient->paymentTransactions()->where('status', 'completed')->count(),
            'failed_transactions' => $apiClient->paymentTransactions()->where('status', 'failed')->count(),
            'total_amount' => $apiClient->paymentTransactions()->where('status', 'completed')->sum('amount'),
            'daily_usage' => $apiClient->daily_used,
            'monthly_usage' => $apiClient->monthly_used,
            'daily_limit' => $apiClient->daily_limit,
            'monthly_limit' => $apiClient->monthly_limit,
        ];

        return view('client.api-clients.statistics', compact('apiClient', 'stats'));
    }
} 