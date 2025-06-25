<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

class ApiClientController extends Controller
{
    /**
     * Display a listing of API clients
     */
    public function index(): View
    {
        $clients = ApiClient::with('user')->latest()->paginate(15);
        return view('admin.api-clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new API client
     */
    public function create(): View
    {
        $users = \App\Models\User::role('client')->get();
        return view('admin.api-clients.create', compact('users'));
    }

    /**
     * Store a newly created API client
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company_name' => 'nullable|string|max:255',
            'is_sandbox' => 'boolean',
            'daily_limit' => 'nullable|numeric|min:0',
            'monthly_limit' => 'nullable|numeric|min:0',
            'allowed_ips' => 'nullable|string',
            'webhook_urls' => 'nullable|string',
            'allowed_currencies' => 'nullable|string',
            'expires_at' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = ApiClient::generateCredentials();

        $client = ApiClient::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
            'is_sandbox' => $request->boolean('is_sandbox', true),
            'daily_limit' => $request->daily_limit,
            'monthly_limit' => $request->monthly_limit,
            'allowed_ips' => $request->allowed_ips ? 
                json_encode(array_map('trim', explode(',', $request->allowed_ips))) : null,
            'webhook_urls' => $request->webhook_urls ? 
                json_encode(array_map('trim', explode(',', $request->webhook_urls))) : null,
            'allowed_currencies' => $request->allowed_currencies ? 
                json_encode(array_map('trim', explode(',', strtoupper($request->allowed_currencies)))) : null,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.api-clients.show', $client)
            ->with('success', 'API Client created successfully');
    }

    /**
     * Display the specified API client
     */
    public function show(ApiClient $apiClient): View
    {
        $apiClient->load(['user', 'paymentTransactions']);
        return view('admin.api-clients.show', compact('apiClient'));
    }

    /**
     * Show the form for editing the specified API client
     */
    public function edit(ApiClient $apiClient): View
    {
        $users = \App\Models\User::role('client')->get();
        return view('admin.api-clients.edit', compact('apiClient', 'users'));
    }

    /**
     * Update the specified API client
     */
    public function update(Request $request, ApiClient $apiClient): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'company_name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'is_sandbox' => 'boolean',
            'daily_limit' => 'nullable|numeric|min:0',
            'monthly_limit' => 'nullable|numeric|min:0',
            'allowed_ips' => 'nullable|string',
            'webhook_urls' => 'nullable|string',
            'allowed_currencies' => 'nullable|string',
            'expires_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $apiClient->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'email' => $request->email,
            'company_name' => $request->company_name,
            'is_active' => $request->boolean('is_active', true),
            'is_sandbox' => $request->boolean('is_sandbox', true),
            'daily_limit' => $request->daily_limit,
            'monthly_limit' => $request->monthly_limit,
            'allowed_ips' => $request->allowed_ips ? 
                json_encode(array_map('trim', explode(',', $request->allowed_ips))) : null,
            'webhook_urls' => $request->webhook_urls ? 
                json_encode(array_map('trim', explode(',', $request->webhook_urls))) : null,
            'allowed_currencies' => $request->allowed_currencies ? 
                json_encode(array_map('trim', explode(',', strtoupper($request->allowed_currencies)))) : null,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('admin.api-clients.show', $apiClient)
            ->with('success', 'API Client updated successfully');
    }

    /**
     * Remove the specified API client
     */
    public function destroy(ApiClient $apiClient): RedirectResponse
    {
        $apiClient->delete();

        return redirect()->route('admin.api-clients.index')
            ->with('success', 'API Client deleted successfully');
    }

    /**
     * Generate new API credentials
     */
    public function regenerateCredentials(ApiClient $apiClient): RedirectResponse
    {
        $credentials = ApiClient::generateCredentials();
        
        $apiClient->update([
            'api_key' => $credentials['api_key'],
            'secret_key' => $credentials['secret_key'],
        ]);

        return redirect()->route('admin.api-clients.show', $apiClient)
            ->with('success', 'API credentials regenerated successfully')
            ->with('new_credentials', $credentials);
    }

    /**
     * Toggle client status
     */
    public function toggleStatus(ApiClient $apiClient): RedirectResponse
    {
        $apiClient->update([
            'is_active' => !$apiClient->is_active
        ]);

        $status = $apiClient->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.api-clients.show', $apiClient)
            ->with('success', "API Client {$status} successfully");
    }

    /**
     * Reset usage statistics
     */
    public function resetUsage(ApiClient $apiClient): RedirectResponse
    {
        $apiClient->update([
            'daily_used' => 0,
            'monthly_used' => 0,
        ]);

        return redirect()->route('admin.api-clients.show', $apiClient)
            ->with('success', 'Usage statistics reset successfully');
    }

    /**
     * Get client transactions (API endpoint)
     */
    public function getTransactions(ApiClient $apiClient): JsonResponse
    {
        $transactions = $apiClient->paymentTransactions()
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions->items(),
            'pagination' => [
                'current_page' => $transactions->currentPage(),
                'per_page' => $transactions->perPage(),
                'total' => $transactions->total(),
                'last_page' => $transactions->lastPage(),
            ]
        ]);
    }
} 