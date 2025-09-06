@extends('client._layouts.app')

@section('title', 'Settlement Center')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settlement Center</h1>
            <p class="text-gray-600 mt-1">Manage your payment gateway settlements and wallet balances</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('client.payment-gateway.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                <i class="fa-solid fa-arrow-left mr-2"></i>
                Back to Gateway
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($totalBalance, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Available for settlement
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Requested</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">${{ number_format($settlementStats['total_requested'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-hand-holding-dollar text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                All time settlements
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Fees</p>
                    <p class="text-2xl font-bold text-orange-600 mt-1">${{ number_format($settlementStats['total_fees'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-percentage text-orange-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Settlement fees paid
            </p>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $settlementStats['pending_count'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                Awaiting processing
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Settlement Request Form -->
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Request Settlement</h2>
                <p class="text-sm text-gray-600 mt-1">Transfer your wallet balance to your account</p>
            </div>
            <div class="p-6">
                @if($totalBalance > 0)
                    <form id="settlement-form" class="space-y-4">
                        @csrf
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Available Balance</span>
                                <span class="text-lg font-bold text-gray-900">${{ number_format($totalBalance, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Settlement Fee</span>
                                <span class="text-sm text-gray-900">{{ $paymentSettings->getFeeDescription() }}</span>
                            </div>
                        </div>

                        <div>
                            <label for="settlement_amount" class="block text-sm font-medium text-gray-700 mb-2">Settlement Amount *</label>
                            <input type="number" id="settlement_amount" name="settlement_amount" step="0.01" min="0.01" max="{{ $totalBalance }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="0.00" required>
                            <p class="text-xs text-gray-500 mt-1">Minimum: $0.01, Maximum: ${{ number_format($totalBalance, 2) }}</p>
                        </div>



                        <div>
                            <label for="settlement_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea id="settlement_notes" name="notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Any additional information..."></textarea>
                        </div>

                        <!-- Fee Calculation Preview -->
                        <div id="fee-preview" class="bg-blue-50 p-4 rounded-lg hidden">
                            <h4 class="font-medium text-blue-900 mb-2">Settlement Summary</h4>
                            <div class="space-y-1 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Settlement Amount:</span>
                                    <span class="text-blue-900 font-medium" id="preview-amount">$0.00</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-blue-700">Fee ({{ $paymentSettings->getFeeDescription() }}):</span>
                                    <span class="text-blue-900 font-medium" id="preview-fee">$0.00</span>
                                </div>
                                <hr class="border-blue-200">
                                <div class="flex justify-between">
                                    <span class="text-blue-700 font-medium">You will receive:</span>
                                    <span class="text-blue-900 font-bold" id="preview-net">$0.00</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="submit-settlement"
                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            Submit Settlement Request
                        </button>
                    </form>
                @else
                    <div class="text-center py-8">
                        <i class="fa-solid fa-wallet text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Funds Available</h3>
                        <p class="text-gray-600">You don't have any funds available for settlement at the moment.</p>
                        <a href="{{ route('client.payment-gateway.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            View Payment Gateway
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Wallet Summary & Successful Payments -->
        <div class="bg-white rounded-xl border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Wallet Summary & Recent Payments</h2>
                <p class="text-sm text-gray-600 mt-1">Current balances and recent completed transactions</p>
            </div>


            <div class="p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">Recent Successful Payments</h3>
                @if($successfulPayments->count() > 0)
                    <div class="space-y-4">
                        @foreach($successfulPayments as $payment)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fa-solid fa-check-circle text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $payment->apiClient->name ?? 'API Client' }}</p>
                                        <p class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $payment->gateway_name)) }} • {{ $payment->created_at->format('M d, Y H:i') }}</p>
                                        <p class="text-xs text-gray-400 font-mono">{{ $payment->transaction_id }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="space-y-1">
                                        <p class="font-bold text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination for successful payments -->
                    <div class="mt-6">
                        {{ $successfulPayments->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fa-solid fa-credit-card text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Successful Payments</h3>
                        <p class="text-gray-600">You don't have any completed payment transactions yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Settlement History -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Settlement History</h2>
            <p class="text-sm text-gray-600 mt-1">Track your settlement requests</p>
        </div>
        <div class="p-6">
            @if($settlements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Reference</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Amount</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Fee</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Net Amount</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Method</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Status</th>
                                <th class="text-left py-3 px-4 font-medium text-gray-700">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($settlements as $settlement)
                                <tr class="border-b border-gray-100">
                                    <td class="py-3 px-4">
                                        <span class="font-mono text-sm text-gray-900">{{ $settlement->reference_id }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-gray-900">${{ number_format($settlement->amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-gray-600">${{ number_format($settlement->fee_amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-green-600">${{ number_format($settlement->net_amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-gray-600">{{ $settlement->getMethodDisplayName() }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $settlement->getStatusBadgeClass() }}">
                                            {{ ucfirst($settlement->status) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="text-sm text-gray-500">{{ $settlement->created_at->format('M d, Y H:i') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $settlements->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fa-solid fa-history text-gray-300 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Settlement History</h3>
                    <p class="text-gray-600">You haven't made any settlement requests yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const settlementForm = document.getElementById('settlement-form');
    const settlementAmountInput = document.getElementById('settlement_amount');
    const feePreview = document.getElementById('fee-preview');
    const previewAmount = document.getElementById('preview-amount');
    const previewFee = document.getElementById('preview-fee');
    const previewNet = document.getElementById('preview-net');
    const submitBtn = document.getElementById('submit-settlement');

    const feeType = '{{ $paymentSettings->fee_type }}';
    const feeValue = {{ $paymentSettings->getFeeValue() }};
    const totalBalance = {{ $totalBalance }};

    // Show fee preview when amount changes
    settlementAmountInput.addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;

        if (amount > 0) {
            let fee = 0;
            if (feeType === 'percentage') {
                fee = (amount * feeValue) / 100;
            } else {
                fee = feeValue;
            }

            const netAmount = amount - fee;

            previewAmount.textContent = '$' + amount.toFixed(2);
            previewFee.textContent = '$' + fee.toFixed(2);
            previewNet.textContent = '$' + netAmount.toFixed(2);

            feePreview.classList.remove('hidden');
        } else {
            feePreview.classList.add('hidden');
        }
    });

    // Handle form submission
    if (settlementForm) {
        settlementForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const originalText = submitBtn.textContent;

            // Disable submit button and show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            try {
                const formData = new FormData(settlementForm);

                const response = await fetch('{{ route("client.payment-gateway.settlement.process") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('Settlement request submitted successfully! Reference ID: ' + result.data.reference_id);
                    window.location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Settlement request failed:', error);
                alert('Failed to submit settlement request. Please try again.');
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    }
});
</script>
@endsection
