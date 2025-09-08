<!-- Filters -->
<div class="flex flex-wrap items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
    <form method="GET" action="{{ route('client.payment-gateway.index') }}" class="flex flex-wrap items-center gap-4 w-full">
        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Status:</label>
            <select name="status" class="form-select text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <option value="">All Status</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Currency:</label>
            <select name="currency" class="form-select text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
                <option value="">All Currencies</option>
                @foreach($stats['currencies'] as $currency)
                    <option value="{{ $currency }}" {{ request('currency') === $currency ? 'selected' : '' }}>{{ $currency }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center space-x-2">
            <label class="text-sm font-medium text-gray-700">Date Range:</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-input text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
            <span class="text-gray-500">to</span>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-input text-sm border-gray-300 rounded-lg focus:ring-black focus:border-black">
        </div>

        <button type="submit" class="px-4 py-2 bg-black text-white text-sm rounded-lg hover:bg-gray-800 transition-all">
            <i class="fa-solid fa-filter mr-2"></i>
            Apply Filters
        </button>

        @if(request()->hasAny(['status', 'currency', 'from_date', 'to_date']))
            <a href="{{ route('client.payment-gateway.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-all">
                <i class="fa-solid fa-times mr-2"></i>
                Clear Filters
            </a>
        @endif
    </form>
</div>

<!-- Transactions Table -->
<div class="overflow-hidden rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
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
                            <a href="{{ route('client.payment-gateway.transaction.show', $transaction->transaction_id) }}"
                            >
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_id }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($transaction->description, 30) }}</div>
                            </div>
                            </a>
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


                            {{ ucfirst($transaction->payment_method) }}
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
    <!-- Pagination -->
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-700">
            Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions
        </div>

        @if($transactions->hasPages())
            <div class="flex items-center space-x-2">
                {{-- Previous Button --}}
                @if($transactions->onFirstPage())
                    <span class="px-3 py-1 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left mr-1"></i>Previous
                    </span>
                @else
                    <a href="{{ $transactions->appends(request()->query())->previousPageUrl() }}"
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-chevron-left mr-1"></i>Previous
                    </a>
                @endif

                {{-- Page Numbers --}}
                @php
                    $start = max(1, $transactions->currentPage() - 2);
                    $end = min($transactions->lastPage(), $transactions->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $transactions->appends(request()->query())->url(1) }}"
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">1</a>
                    @if($start > 2)
                        <span class="px-2 py-1 text-gray-400">...</span>
                    @endif
                @endif

                @for($i = $start; $i <= $end; $i++)
                    @if($i == $transactions->currentPage())
                        <span class="px-3 py-1 bg-black text-white rounded text-sm">{{ $i }}</span>
                    @else
                        <a href="{{ $transactions->appends(request()->query())->url($i) }}"
                           class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">{{ $i }}</a>
                    @endif
                @endfor

                @if($end < $transactions->lastPage())
                    @if($end < $transactions->lastPage() - 1)
                        <span class="px-2 py-1 text-gray-400">...</span>
                    @endif
                    <a href="{{ $transactions->appends(request()->query())->url($transactions->lastPage()) }}"
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">{{ $transactions->lastPage() }}</a>
                @endif

                {{-- Next Button --}}
                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->appends(request()->query())->nextPageUrl() }}"
                       class="px-3 py-1 border border-gray-300 rounded text-sm hover:bg-gray-50 transition-colors">
                        Next<i class="fa-solid fa-chevron-right ml-1"></i>
                    </a>
                @else
                    <span class="px-3 py-1 border border-gray-200 rounded text-sm text-gray-400 cursor-not-allowed">
                        Next<i class="fa-solid fa-chevron-right ml-1"></i>
                    </span>
                @endif
            </div>
        @endif
    </div>
@endif
