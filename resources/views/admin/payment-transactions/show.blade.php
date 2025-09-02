@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Payment Transaction Details</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Transaction ID: {{ $paymentTransaction->transaction_id }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.payment-transactions.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-colors">
                <i class="material-symbols-outlined mr-2 text-[18px]">arrow_back</i>
                Back to List
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-emerald-500 mr-2">check_circle</i>
                <p class="text-emerald-600 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Transaction Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Transaction Overview -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Transaction Overview</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Transaction ID</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white font-mono">{{ $paymentTransaction->transaction_id }}</p>
                    </div>
                    @if($paymentTransaction->client_order_id)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Client Order ID</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->client_order_id }}</p>
                        </div>
                    @endif
                    @if($paymentTransaction->gateway_transaction_id)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gateway Transaction ID</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white font-mono">{{ $paymentTransaction->gateway_transaction_id }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Checkout Session ID</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white font-mono">{{ $paymentTransaction->checkout_session_id }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Amount</label>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                            {{ number_format($paymentTransaction->amount, 2) }} {{ strtoupper($paymentTransaction->currency) }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                        @php
                            $statusColors = [
                                'checkout_pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400',
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400',
                                'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400',
                                'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400',
                                'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400',
                                'cancelled' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-400',
                                'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400',
                            ];
                        @endphp
                        <span class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$paymentTransaction->status] ?? 'bg-slate-100 text-slate-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $paymentTransaction->status)) }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gateway</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst($paymentTransaction->gateway_name) }}</p>
                    </div>
                    @if($paymentTransaction->gateway_status)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gateway Status</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->gateway_status }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Created At</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Updated At</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->updated_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Customer Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($paymentTransaction->customer_name)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->customer_name }}</p>
                        </div>
                    @endif
                    @if($paymentTransaction->customer_email)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->customer_email }}</p>
                        </div>
                    @endif
                    @if($paymentTransaction->customer_phone)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Phone</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->customer_phone }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">API Client User</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->apiClient->user->name ?? 'N/A' }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $paymentTransaction->apiClient->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Method Details -->
            @if($paymentTransaction->paymentMethod)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Payment Method Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Type</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $paymentTransaction->paymentMethod->payment_method_type)) }}</p>
                        </div>
                        @if($paymentTransaction->paymentMethod->payment_method_subtype)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Subtype</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $paymentTransaction->paymentMethod->payment_method_subtype)) }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->card_brand)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Card Brand</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst($paymentTransaction->paymentMethod->card_brand) }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->card_type)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Card Type</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst($paymentTransaction->paymentMethod->card_type) }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->card_last_four)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Last Four Digits</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white font-mono">****{{ $paymentTransaction->paymentMethod->card_last_four }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->card_exp_month && $paymentTransaction->paymentMethod->card_exp_year)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Expiry</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->paymentMethod->card_exp_month }}/{{ $paymentTransaction->paymentMethod->card_exp_year }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->bank_name)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Bank Name</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->paymentMethod->bank_name }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->wallet_provider)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Wallet Provider</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $paymentTransaction->paymentMethod->wallet_provider)) }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->paymentMethod->crypto_currency)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Cryptocurrency</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ strtoupper($paymentTransaction->paymentMethod->crypto_currency) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Fees and Amounts -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Fees and Amounts</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Transaction Amount</label>
                        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                            {{ number_format($paymentTransaction->amount, 2) }} {{ strtoupper($paymentTransaction->currency) }}
                        </p>
                    </div>
                    @if($paymentTransaction->gateway_fee)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Gateway Fee</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">
                                {{ number_format($paymentTransaction->gateway_fee, 2) }} {{ strtoupper($paymentTransaction->currency) }}
                            </p>
                        </div>
                    @endif
                    @if($paymentTransaction->our_fee)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Our Fee</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">
                                {{ number_format($paymentTransaction->our_fee, 2) }} {{ strtoupper($paymentTransaction->currency) }}
                            </p>
                        </div>
                    @endif
                    @if($paymentTransaction->net_amount)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Net Amount</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">
                                {{ number_format($paymentTransaction->net_amount, 2) }} {{ strtoupper($paymentTransaction->currency) }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Failure Reason -->
            @if($paymentTransaction->failure_reason)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Failure Reason</h2>
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $paymentTransaction->failure_reason }}</p>
                </div>
            @endif

            <!-- Gateway Response -->
            @if($paymentTransaction->gateway_response)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Gateway Response</h2>
                    <pre class="bg-slate-50 dark:bg-slate-700 p-4 rounded-lg text-xs text-slate-800 dark:text-slate-200 overflow-x-auto">{{ json_encode($paymentTransaction->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- API Client Information -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">API Client</h2>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Client Name</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->apiClient->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Company</label>
                        <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->apiClient->company_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Environment</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentTransaction->apiClient->is_sandbox ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' }}">
                            {{ $paymentTransaction->apiClient->is_sandbox ? 'Sandbox' : 'Production' }}
                        </span>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                        <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $paymentTransaction->apiClient->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' }}">
                            {{ $paymentTransaction->apiClient->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Webhook Information -->
            @if($paymentTransaction->webhook_attempts > 0)
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Webhook Information</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Attempts</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->webhook_attempts }}</p>
                        </div>
                        @if($paymentTransaction->webhook_sent_at)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Last Sent</label>
                                <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $paymentTransaction->webhook_sent_at->format('M d, Y H:i:s') }}</p>
                            </div>
                        @endif
                        @if($paymentTransaction->webhook_responses)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Responses</label>
                                <div class="mt-1 space-y-2">
                                    @foreach($paymentTransaction->webhook_responses as $response)
                                        <div class="text-xs bg-slate-50 dark:bg-slate-700 p-2 rounded">
                                            <div class="font-medium">{{ $response['timestamp'] }}</div>
                                            <div class="text-slate-600 dark:text-slate-400">{{ json_encode($response['response']) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Technical Details -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Technical Details</h2>
                <div class="space-y-3">
                    @if($paymentTransaction->ip_address)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">IP Address</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white font-mono">{{ $paymentTransaction->ip_address }}</p>
                        </div>
                    @endif
                    @if($paymentTransaction->user_agent)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">User Agent</label>
                            <p class="mt-1 text-xs text-slate-900 dark:text-white break-all">{{ $paymentTransaction->user_agent }}</p>
                        </div>
                    @endif
                    @if($paymentTransaction->stage)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Stage</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $paymentTransaction->stage)) }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
