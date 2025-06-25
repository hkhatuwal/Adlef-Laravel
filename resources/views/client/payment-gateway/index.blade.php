@extends('client._layouts.app')

@section('title', 'Payment Gateway')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Payment Gateway</h1>
            <p class="text-gray-600 mt-1">Manage your payment transactions and API integrations</p>
        </div>
        <div class="flex space-x-3">
            <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                <i class="fa-solid fa-download mr-2"></i>
                Export
            </button>

        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Transactions</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_transactions']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-credit-card text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4 flex items-center">
                <span class="text-green-600 mr-1">
                    <i class="fa-solid fa-arrow-up text-xs"></i>
                    12%
                </span>
                from last month
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Successful</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['successful_transactions']) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                {{ $stats['total_transactions'] > 0 ? number_format(($stats['successful_transactions']/$stats['total_transactions'])*100, 1) : 0 }}% success rate
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Failed</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['failed_transactions']) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-exclamation-circle text-red-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                {{ $stats['total_transactions'] > 0 ? number_format(($stats['failed_transactions']/$stats['total_transactions'])*100, 1) : 0 }}% failure rate
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['total_amount'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4 flex items-center">
                <span class="text-green-600 mr-1">
                    <i class="fa-solid fa-arrow-up text-xs"></i>
                    8%
                </span>
                from last month
            </p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl border border-gray-200">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button class="tab-button active flex items-center py-4 px-1 border-b-2 border-black font-medium text-sm text-black" data-tab="transactions">
                    <i class="fa-solid fa-list mr-2"></i>
                    Transactions
                </button>
                <button class="tab-button flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="api-keys">
                    <i class="fa-solid fa-key mr-2"></i>
                    API Keys
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Transactions Tab -->
            <div id="transactions-tab" class="tab-content">
                <!-- Filters -->
                <div class="flex flex-wrap items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Status:</label>
                        <select class="form-select text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                            <option value="">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Currency:</label>
                        <select class="form-select text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                            <option value="">All Currencies</option>
                            @foreach($stats['currencies'] as $currency)
                                <option value="{{ $currency }}">{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Date Range:</label>
                        <input type="date" name="from_date" class="form-input text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                        <span class="text-gray-500">to</span>
                        <input type="date" name="to_date" class="form-input text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                    </div>

                    <button class="px-4 py-2 bg-black text-white text-sm rounded-lg hover:bg-gray-800 transition-all">
                        <i class="fa-solid fa-filter mr-2"></i>
                        Apply Filters
                    </button>
                </div>

                <!-- Transactions Table -->
                <div class="overflow-hidden rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                <i class="fa-solid fa-credit-card text-gray-600"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_id }}</div>
                                                <div class="text-sm text-gray-500">{{ Str::limit($transaction->description, 30) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</div>
                                        @if($transaction->customer_email)
                                            <div class="text-sm text-gray-500">{{ $transaction->customer_email }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'completed' => 'bg-green-100 text-green-800',
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'processing' => 'bg-blue-100 text-blue-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'refunded' => 'bg-gray-100 text-gray-800',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                                <i class="fa-solid fa-credit-card text-xs text-gray-600"></i>
                                            </div>
                                            {{ ucfirst($transaction->gateway_name) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $transaction->created_at->format('M j, Y') }}</div>
                                        <div class="text-gray-500">{{ $transaction->created_at->format('g:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('client.payment-gateway.transaction.show', $transaction->transaction_id) }}"
                                               class="text-black hover:text-gray-600 transition-colors">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                            <button class="text-gray-400 hover:text-gray-600 transition-colors"
                                                    onclick="copyToClipboard('{{ $transaction->transaction_id }}')">
                                                <i class="fa-solid fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <i class="fa-solid fa-credit-card text-gray-400 text-2xl"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No transactions found</h3>
                                            <p class="text-gray-500 mb-4">Get started by creating your first payment transaction.</p>
                                            <button class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-all">
                                                <i class="fa-solid fa-plus mr-2"></i>
                                                Create Transaction
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->count() > 0)
                    <!-- Pagination placeholder -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="text-sm text-gray-700">
                            Showing {{ $transactions->count() }} of {{ $totalTransactions }} transactions
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">Previous</button>
                            <button class="px-3 py-1 bg-black text-white rounded text-sm">1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">2</button>
                            <button class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50">Next</button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- API Keys Tab -->
            <div id="api-keys-tab" class="tab-content hidden">
                @include('client.payment-gateway.components.api-keys')
            </div>
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script src="{{ asset('assets/js/payment-gateway/payment-gateway.js') }}"></script>
@endsection
