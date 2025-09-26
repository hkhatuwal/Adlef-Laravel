<!-- API Keys Management Section -->
<div class="space-y-6">
    <!-- Header with Create Button -->
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium text-gray-900">API Keys</h3>
            <p class="text-sm text-gray-500">Manage your API keys for payment gateway integration</p>
        </div>
        <button onclick="openCreateApiKeyModal()" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-all duration-200 shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i>
            Create API Key
        </button>
    </div>

    @if($apiClients->count() > 0)
        <!-- API Keys List -->
        <div class="space-y-6">
            @foreach($apiClients as $client)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200">
                    <!-- Header Section -->
                    <div class="px-6 py-4 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <i class="fa-solid fa-key text-white text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $client->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $client->company_name ?: 'No company specified' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $client->is_active ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $client->is_active ? 'bg-green-500' : 'bg-red-500' }} mr-1.5"></span>
                                    {{ $client->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <!-- Actions Dropdown -->
                                <div class="relative">
                                    <button onclick="toggleDropdown('actions-{{ $client->id }}')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div id="actions-{{ $client->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                        <div class="py-1">
                                            <button onclick="editApiClient({{ $client->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                <i class="fa-solid fa-edit mr-2 text-blue-500"></i>
                                                Edit Details
                                            </button>
                                            <button onclick="regenerateApiKey({{ $client->id }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                <i class="fa-solid fa-refresh mr-2 text-yellow-500"></i>
                                                Regenerate Keys
                                            </button>
                                            <button onclick="toggleApiClientStatus({{ $client->id }}, {{ $client->is_active ? 'false' : 'true' }})" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center">
                                                <i class="fa-solid fa-{{ $client->is_active ? 'ban' : 'check' }} mr-2 text-{{ $client->is_active ? 'red' : 'green' }}-500"></i>
                                                {{ $client->is_active ? 'Disable' : 'Enable' }} API Key
                                            </button>
                                            <hr class="my-1">
                                            <button onclick="deleteApiClient({{ $client->id }})" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                <i class="fa-solid fa-trash mr-2"></i>
                                                Delete API Key
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="px-6 py-6">
                        <!-- API Credentials Section -->
                        <div class="mb-6">
                            <h5 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fa-solid fa-lock mr-2 text-gray-400"></i>
                                API Credentials
                            </h5>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">API Key</label>
                                    <div class="mt-2 flex items-center space-x-2">
                                        <code class="text-sm bg-white px-3 py-2 rounded-md border font-mono text-gray-800 flex-1">{{ Str::mask($client->api_key, '*', 8, -8) }}</code>
                                        <button onclick="copyToClipboard('{{ $client->api_key }}')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors" title="Copy API Key">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                        <button onclick="toggleApiKeyVisibility('api-{{ $client->id }}')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors" title="Toggle Visibility">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="api-{{ $client->id }}" class="hidden mt-2">
                                        <code class="text-sm bg-white px-3 py-2 rounded-md border font-mono text-gray-800 block break-all">{{ $client->api_key }}</code>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wide">API Secret</label>
                                    <div class="mt-2 flex items-center space-x-2">
                                        <code class="text-sm bg-white px-3 py-2 rounded-md border font-mono text-gray-800 flex-1">{{ Str::mask($client->secret_key, '*', 8, -8) }}</code>
                                        <button onclick="copyToClipboard('{{ $client->secret_key }}')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors" title="Copy Secret Key">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                        <button onclick="toggleApiKeyVisibility('secret-{{ $client->id }}')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-md transition-colors" title="Toggle Visibility">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                    <div id="secret-{{ $client->id }}" class="hidden mt-2">
                                        <code class="text-sm bg-white px-3 py-2 rounded-md border font-mono text-gray-800 block break-all">{{ $client->secret_key }}</code>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Usage Statistics -->
                        <div class="mb-6">
                            <h5 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fa-solid fa-chart-bar mr-2 text-gray-400"></i>
                                Usage Statistics
                            </h5>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-xs font-medium text-blue-600 uppercase tracking-wide">Daily Usage</div>
                                        <i class="fa-solid fa-calendar-day text-blue-500"></i>
                                    </div>
                                    <div class="text-lg font-bold text-blue-900">
                                        ${{ number_format($client->daily_used, 2) }}
                                    </div>
                                    @if($client->daily_limit)
                                        <div class="text-xs text-blue-700 mt-1">of ${{ number_format($client->daily_limit, 2) }}</div>
                                        <div class="w-full bg-blue-200 rounded-full h-2 mt-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ min(($client->daily_used / $client->daily_limit) * 100, 100) }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-xs font-medium text-green-600 uppercase tracking-wide">Monthly Usage</div>
                                        <i class="fa-solid fa-calendar-alt text-green-500"></i>
                                    </div>
                                    <div class="text-lg font-bold text-green-900">
                                        ${{ number_format($client->monthly_used, 2) }}
                                    </div>
                                    @if($client->monthly_limit)
                                        <div class="text-xs text-green-700 mt-1">of ${{ number_format($client->monthly_limit, 2) }}</div>
                                        <div class="w-full bg-green-200 rounded-full h-2 mt-2">
                                            <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ min(($client->monthly_used / $client->monthly_limit) * 100, 100) }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-xs font-medium text-purple-600 uppercase tracking-wide">Last Used</div>
                                        <i class="fa-solid fa-clock text-purple-500"></i>
                                    </div>
                                    <div class="text-lg font-bold text-purple-900">
                                        {{ $client->last_used_at ? $client->last_used_at->diffForHumans() : 'Never' }}
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-xs font-medium text-orange-600 uppercase tracking-wide">Total Transactions</div>
                                        <i class="fa-solid fa-receipt text-orange-500"></i>
                                    </div>
                                    <div class="text-lg font-bold text-orange-900">
                                        {{ number_format($client->paymentTransactions()->count()) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h5 class="text-sm font-medium text-gray-900 mb-3 flex items-center">
                                <i class="fa-solid fa-info-circle mr-2 text-gray-400"></i>
                                Configuration Details
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email Contact</span>
                                        <i class="fa-solid fa-envelope text-gray-400"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $client->email ?: 'Not specified' }}</div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Allowed Currencies</span>
                                        <i class="fa-solid fa-coins text-gray-400"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($client->allowed_currencies && count($client->allowed_currencies) > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800 mr-1">
                                                {{ implode(', ', $client->allowed_currencies) }}
                                            </span>
                                        @else
                                            <span class="text-green-600">All currencies</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">IP Restrictions</span>
                                        <i class="fa-solid fa-shield-alt text-gray-400"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        @if($client->allowed_ips && count($client->allowed_ips) > 0)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                                {{ count($client->allowed_ips) }} IP(s) allowed
                                            </span>
                                        @else
                                            <span class="text-green-600">No restrictions</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Created</span>
                                        <i class="fa-solid fa-calendar-plus text-gray-400"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $client->created_at->format('M j, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination for API Keys (if needed) -->

    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-key text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No API Keys Found</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Create your first API key to start integrating with our payment gateway and begin processing transactions.</p>
            <button onclick="openCreateApiKeyModal()" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 transition-all duration-200 shadow-sm">
                <i class="fa-solid fa-plus mr-2"></i>
                Create Your First API Key
            </button>
        </div>
    @endif
</div>

<!-- Create API Key Modal -->
@include('client.payment-gateway.components.create-apikey-modal')

<!-- Edit API Key Modal -->
@include('client.payment-gateway.components.edit-apikey-modal')

<meta name="api-keys-create-url" content="{{ route('client.payment-gateway.api-keys.store') }}">

<script>
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const allDropdowns = document.querySelectorAll('[id^="actions-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== id) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('[id^="actions-"]');
    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target) && !event.target.closest('button[onclick*="toggleDropdown"]')) {
            dropdown.classList.add('hidden');
        }
    });
});
</script>
