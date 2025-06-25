<!-- API Keys Management Section -->
<div class="space-y-6">
    <!-- Header with Create Button -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">API Keys</h3>
            <p class="text-sm text-gray-500">Manage your API keys for payment gateway integration</p>
        </div>
        <button onclick="openCreateApiKeyModal()" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-all">
            <i class="fa-solid fa-plus mr-2"></i>
            Create API Key
        </button>
    </div>

    @if($apiClients->count() > 0)
        <!-- API Keys List -->
        <div class="space-y-4">
            @foreach($apiClients as $client)
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $client->name }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $client->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($client->is_sandbox)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Sandbox
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700">API Key</label>
                                    <div class="mt-1 flex items-center space-x-2">
                                        <code class="text-sm bg-white px-3 py-2 rounded border font-mono">{{ Str::mask($client->api_key, '*', 8, -8) }}</code>
                                        <button onclick="copyToClipboard('{{ $client->api_key }}')" class="text-gray-400 hover:text-gray-600">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                        <button onclick="toggleApiKeyVisibility('api-{{ $client->id }}')" class="text-gray-400 hover:text-gray-600">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="api-{{ $client->id }}" class="hidden mt-1">
                                        <code class="text-sm bg-white px-3 py-2 rounded border font-mono block">{{ $client->api_key }}</code>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700">Company</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $client->company_name ?: 'Not specified' }}</div>
                                </div>

                                <div>
                                    <label class="text-sm font-medium text-gray-700">Email</label>
                                    <div class="mt-1 text-sm text-gray-900">{{ $client->email ?: 'Not specified' }}</div>
                                </div>
                            </div>

                            <!-- Usage Stats -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-xs font-medium text-gray-500 uppercase">Daily Usage</div>
                                    <div class="text-sm font-semibold text-gray-900 mt-1">
                                        ${{ number_format($client->daily_used, 2) }}
                                        @if($client->daily_limit)
                                            / ${{ number_format($client->daily_limit, 2) }}
                                        @endif
                                    </div>
                                    @if($client->daily_limit)
                                        <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                                            <div class="bg-blue-600 h-1 rounded-full" style="width: {{ min(($client->daily_used / $client->daily_limit) * 100, 100) }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-xs font-medium text-gray-500 uppercase">Monthly Usage</div>
                                    <div class="text-sm font-semibold text-gray-900 mt-1">
                                        ${{ number_format($client->monthly_used, 2) }}
                                        @if($client->monthly_limit)
                                            / ${{ number_format($client->monthly_limit, 2) }}
                                        @endif
                                    </div>
                                    @if($client->monthly_limit)
                                        <div class="w-full bg-gray-200 rounded-full h-1 mt-2">
                                            <div class="bg-green-600 h-1 rounded-full" style="width: {{ min(($client->monthly_used / $client->monthly_limit) * 100, 100) }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-xs font-medium text-gray-500 uppercase">Last Used</div>
                                    <div class="text-sm font-semibold text-gray-900 mt-1">
                                        {{ $client->last_used_at ? $client->last_used_at->diffForHumans() : 'Never' }}
                                    </div>
                                </div>

                                <div class="bg-white rounded-lg p-3 border">
                                    <div class="text-xs font-medium text-gray-500 uppercase">Transactions</div>
                                    <div class="text-sm font-semibold text-gray-900 mt-1">
                                        {{ number_format($client->paymentTransactions()->count()) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-gray-700">Allowed Currencies:</span>
                                    <span class="text-gray-900 ml-1">
                                        @if($client->allowed_currencies && count($client->allowed_currencies) > 0)
                                            {{ implode(', ', $client->allowed_currencies) }}
                                        @else
                                            All currencies
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">IP Restrictions:</span>
                                    <span class="text-gray-900 ml-1">
                                        @if($client->allowed_ips && count($client->allowed_ips) > 0)
                                            {{ count($client->allowed_ips) }} IP(s) allowed
                                        @else
                                            No restrictions
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="editApiClient({{ $client->id }})" class="text-gray-400 hover:text-blue-600 transition-colors">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="regenerateApiKey({{ $client->id }})" class="text-gray-400 hover:text-yellow-600 transition-colors" title="Regenerate API Key">
                                <i class="fa-solid fa-refresh"></i>
                            </button>
                            <button onclick="toggleApiClientStatus({{ $client->id }}, {{ $client->is_active ? 'false' : 'true' }})"
                                    class="text-gray-400 hover:text-{{ $client->is_active ? 'red' : 'green' }}-600 transition-colors"
                                    title="{{ $client->is_active ? 'Disable' : 'Enable' }} API Key">
                                <i class="fa-solid fa-{{ $client->is_active ? 'ban' : 'check' }}"></i>
                            </button>
                            <button onclick="deleteApiClient({{ $client->id }})" class="text-gray-400 hover:text-red-600 transition-colors" title="Delete API Key">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination for API Keys (if needed) -->

    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-key text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No API Keys Found</h3>
            <p class="text-gray-500 mb-4">Create your first API key to start integrating with our payment gateway.</p>
            <button onclick="openCreateApiKeyModal()" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-all">
                <i class="fa-solid fa-plus mr-2"></i>
                Create API Key
            </button>
        </div>
    @endif
</div>

<!-- Create API Key Modal -->
@include('client.payment-gateway.components.create-apikey-modal')
<meta name="api-keys-create-url" content="{{ route('client.payment-gateway.api-keys.store') }}">
