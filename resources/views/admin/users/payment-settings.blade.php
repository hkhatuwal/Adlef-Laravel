@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.show', $user) }}"
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                    <i class="material-symbols-outlined text-2xl">arrow_back</i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Payment Settings</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Manage payment settings for {{ $user->name }}</p>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium shadow-sm
                {{ $paymentSettings->is_active ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-red-50 text-red-700 dark:bg-red-900/50 dark:text-red-400' }}">
                <i class="material-symbols-outlined text-lg mr-2">{{ $paymentSettings->is_active ? 'check_circle' : 'cancel' }}</i>
                {{ $paymentSettings->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Settings Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.users.update-payment-settings', $user) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- API Client Limits -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                            <i class="material-symbols-outlined mr-2">api</i>
                            API Client Limits
                        </h3>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="max_api_clients" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Maximum API Clients
                                </label>
                                <input type="number"
                                       id="max_api_clients"
                                       name="max_api_clients"
                                       value="{{ old('max_api_clients', $paymentSettings->max_api_clients) }}"
                                       min="1"
                                       max="100"
                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                @error('max_api_clients')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                    Current: {{ $userApiClients->count() }} / {{ $paymentSettings->max_api_clients }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Status
                                </label>
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', $paymentSettings->is_active) ? 'checked' : '' }}
                                           class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <label for="is_active" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                        Enable Payment Settings
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settlement Fee Settings -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                            <i class="material-symbols-outlined mr-2">percent</i>
                            Settlement Fee Settings
                        </h3>
                    </div>
                    <div class="p-8">
                        <div class="space-y-6">
                            <!-- Fee Type Selection -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-4">
                                    Fee Type
                                </label>
                                <div class="flex space-x-6">
                                    <div class="flex items-center">
                                        <input type="radio"
                                               id="fee_type_percentage"
                                               name="fee_type"
                                               value="percentage"
                                               {{ old('fee_type', $paymentSettings->fee_type ?? 'percentage') === 'percentage' ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="fee_type_percentage" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                            Percentage (%)
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="radio"
                                               id="fee_type_fixed"
                                               name="fee_type"
                                               value="fixed"
                                               {{ old('fee_type', $paymentSettings->fee_type ?? 'percentage') === 'fixed' ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="fee_type_fixed" class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">
                                            Fixed Amount (USD)
                                        </label>
                                    </div>
                                </div>
                                @error('fee_type')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fee Value Inputs -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Percentage Fee -->
                                <div id="percentage_fee_section" class="fee-input-section">
                                    <label for="fee_percentage" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Fee Percentage (%)
                                    </label>
                                    <div class="relative">
                                        <input type="number"
                                               id="fee_percentage"
                                               name="fee_percentage"
                                               value="{{ old('fee_percentage', $paymentSettings->fee_percentage ?? 0) }}"
                                               step="0.01"
                                               min="0"
                                               max="100"
                                               class="w-full px-4 py-3 pr-8 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 dark:text-slate-400 text-sm">%</span>
                                        </div>
                                    </div>
                                    @error('fee_percentage')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        Example: 2.5% fee on $100 transaction = $2.50
                                    </p>
                                </div>

                                <!-- Fixed Fee -->
                                <div id="fixed_fee_section" class="fee-input-section">
                                    <label for="fee_fixed" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        Fixed Fee Amount (USD)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 dark:text-slate-400 text-sm">$</span>
                                        </div>
                                        <input type="number"
                                               id="fee_fixed"
                                               name="fee_fixed"
                                               value="{{ old('fee_fixed', $paymentSettings->fee_fixed ?? 0) }}"
                                               step="0.01"
                                               min="0"
                                               max="999999.99"
                                               class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                    </div>
                                    @error('fee_fixed')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                                        Example: $5.00 fee per transaction regardless of amount
                                    </p>
                                </div>
                            </div>

                            <!-- Fee Preview -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <h5 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-3">Fee Preview</h5>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-blue-600 dark:text-blue-400">Sample Transaction ($100):</span>
                                        <span class="font-medium text-blue-800 dark:text-blue-200" id="fee_preview_amount">$0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600 dark:text-blue-400">Sample Transaction ($1,000):</span>
                                        <span class="font-medium text-blue-800 dark:text-blue-200" id="fee_preview_amount_1000">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Provider Settings -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                    <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                            <i class="material-symbols-outlined mr-2">payment</i>
                            Payment Providers & Limits
                        </h3>
                    </div>
                    <div class="p-8">
                        @foreach($availableProviders as $category => $providers)
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-slate-800 dark:text-slate-200 mb-6 capitalize">
                                {{ str_replace('_', ' ', $category) }} Providers
                            </h4>
                            <div class="space-y-6">
                                @foreach($providers as $provider =>$config)
                                <div class="p-6 bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                   id="provider_{{ $category }}_{{ $provider }}"
                                                   name="allowed_payment_providers[{{ $category }}][]"
                                                   value="{{ $provider }}"
                                                   {{ in_array($provider, $paymentSettings->getAllowedProvidersForCategory($category)) ? 'checked' : '' }}
                                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="provider_{{ $category }}_{{ $provider }}" class="ml-3 text-lg font-medium text-slate-900 dark:text-white capitalize">
                                                {{ ucfirst($provider) }}
                                            </label>
                                        </div>
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium
                                            {{ in_array($provider, $paymentSettings->getAllowedProvidersForCategory($category)) ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-400' }}">
                                            {{ in_array($provider, $paymentSettings->getAllowedProvidersForCategory($category)) ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>

                                    <div class="space-y-4">
                                        <!-- Current Usage Stats -->
                                        @if(isset($providerUsageStats[$provider]))
                                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                            <h5 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-3">Current Usage</h5>
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    <span class="text-blue-600 dark:text-blue-400">Daily:</span>
                                                    <span class="font-medium text-blue-800 dark:text-blue-200">
                                                        ${{ number_format($providerUsageStats[$provider]['daily_used'], 2) }}
                                                    </span>
                                                    <span class="text-blue-500 dark:text-blue-300">
                                                        ({{ $providerUsageStats[$provider]['daily_transactions'] }} transactions)
                                                    </span>
                                                </div>
                                                <div>
                                                    <span class="text-blue-600 dark:text-blue-400">Monthly:</span>
                                                    <span class="font-medium text-blue-800 dark:text-blue-200">
                                                        ${{ number_format($providerUsageStats[$provider]['monthly_used'], 2) }}
                                                    </span>
                                                    <span class="text-blue-500 dark:text-blue-300">
                                                        ({{ $providerUsageStats[$provider]['monthly_transactions'] }} transactions)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <!-- Limit Settings -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="daily_limit_{{ $provider }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Daily Limit (USD)
                                                </label>
                                                <input type="number"
                                                       id="daily_limit_{{ $provider }}"
                                                       name="provider_limits[{{ $provider }}][daily_limit]"
                                                       value="{{ old("provider_limits.{$provider}.daily_limit", $paymentSettings->getProviderDailyLimit($provider)) }}"
                                                       step="0.01"
                                                       min="0"
                                                       max="999999.99"
                                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                                @error("provider_limits.{$provider}.daily_limit")
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div>
                                                <label for="monthly_limit_{{ $provider }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                                    Monthly Limit (USD)
                                                </label>
                                                <input type="number"
                                                       id="monthly_limit_{{ $provider }}"
                                                       name="provider_limits[{{ $provider }}][monthly_limit]"
                                                       value="{{ old("provider_limits.{$provider}.monthly_limit", $paymentSettings->getProviderMonthlyLimit($provider)) }}"
                                                       step="0.01"
                                                       min="0"
                                                       max="999999.99"
                                                       class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                                                @error("provider_limits.{$provider}.monthly_limit")
                                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 rounded-xl text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 transition-colors shadow-sm">
                        <i class="material-symbols-outlined text-lg mr-2">save</i>
                        Update Payment Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <!-- Current API Clients -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">api</i>
                        Current API Clients
                    </h3>
                </div>
                <div class="p-8">
                    @if($userApiClients->count() > 0)
                        <div class="space-y-4">
                            @foreach($userApiClients as $client)
                            <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-slate-900 dark:text-white">{{ $client->name }}</h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium
                                        {{ $client->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' }}">
                                        {{ $client->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">{{ $client->masked_api_key }}</p>
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $client->environment_label }}</span>
                                    <span>{{ $client->paymentTransactions->count() }} transactions</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                                <i class="material-symbols-outlined text-3xl text-slate-400">api</i>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400">No API clients created yet</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">analytics</i>
                        Usage Statistics
                    </h3>
                </div>
                <div class="p-8">
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-600 dark:text-slate-400">API Clients</span>
                                <span class="text-slate-900 dark:text-white font-medium">{{ $userApiClients->count() }} / {{ $paymentSettings->max_api_clients }}</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, ($userApiClients->count() / $paymentSettings->max_api_clients) * 100) }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-slate-600 dark:text-slate-400">Total Transactions</span>
                                <span class="text-slate-900 dark:text-white font-medium">{{ $userApiClients->sum(fn($client) => $client->paymentTransactions->count()) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('post-script')
    <script src="{{ asset('admin/js/payment-settings/payment-settings.js') }}"></script>
@endsection
@endsection


