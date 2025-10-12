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
            <div class="relative export-dropdown">
                <button id="export-dropdown-btn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all flex items-center">
                    <i class="fa-solid fa-download mr-2"></i>
                    Export
                    <i class="fa-solid fa-chevron-down ml-2 text-xs"></i>
                </button>
                <div id="export-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                    <div class="py-1">
                        <a href="{{ route('client.payment-gateway.export.excel', request()->query()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-file-excel text-green-600 mr-2"></i>
                            Export to Excel
                        </a>
                        <a href="{{ route('client.payment-gateway.export.csv', request()->query()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-file-csv text-blue-600 mr-2"></i>
                            Export to CSV
                        </a>
                        <a href="{{ route('client.payment-gateway.export.pdf', request()->query()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <i class="fa-solid fa-file-pdf text-red-600 mr-2"></i>
                            Export to PDF
                        </a>
                    </div>
                </div>
            </div>
            <a href="{{ route('client.payment-gateway.settlement') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all">
                <i class="fa-solid fa-wallet mr-2"></i>
                Settlement Center
            </a>
            <button id="settlement-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all"
                    data-wallet-balance="{{ $stats['wallet_balance'] ?? 0 }}">
                <i class="fa-solid fa-money-bill-transfer mr-2"></i>
                Quick Settlement
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
                    <p class="text-sm font-medium text-gray-600">Wallet Balance</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">${{ number_format($stats['wallet_balance'], 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-wallet text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4 flex items-center">
                <span class="text-blue-600 mr-1">
                    <i class="fa-solid fa-info-circle text-xs"></i>
                </span>
                Available for settlement
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
                <button class="tab-button flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="generate-link">
                    <i class="fa-solid fa-link mr-2"></i>
                    Generate Payment Link
                </button>
                <button class="tab-button flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="api-keys">
                    <i class="fa-solid fa-key mr-2"></i>
                    API Keys
                </button>
                <button class="tab-button flex items-center py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="api-docs">
                    <i class="fa-solid fa-book mr-2"></i>
                    API Documentation
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Transactions Tab -->
            <div id="transactions-tab" class="tab-content">
                @include('components.payment-gateway-transactions')
            </div>

            <!-- Generate Payment Link Tab -->
            <div id="generate-link-tab" class="tab-content hidden">
                @include('client.payment-gateway.components.generate-payment-link')
            </div>

            <!-- API Keys Tab -->
            <div id="api-keys-tab" class="tab-content hidden">
                @include('client.payment-gateway.components.api-keys')
            </div>

            <!-- API Documentation Tab -->
            <div id="api-docs-tab" class="tab-content hidden">
                @include('client.payment-gateway.components.api-documentation')
            </div>
        </div>
    </div>
</div>

<!-- Settlement Modal -->
<div id="settlement-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Request Settlement</h3>
                <button id="close-settlement-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fa-solid fa-times text-xl"></i>
                </button>
            </div>

            <form id="settlement-form">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Available Balance</label>
                        <div class="bg-gray-50 p-3 rounded-lg">
                            <span class="text-lg font-semibold text-gray-900" id="available-balance">$0.00</span>
                        </div>
                    </div>

                    <div>
                        <label for="settlement_amount" class="block text-sm font-medium text-gray-700 mb-2">Settlement Amount *</label>
                        <input type="number" id="settlement_amount" name="settlement_amount" step="0.01" min="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00" required>
                    </div>

                    <div>
                        <label for="settlement_method" class="block text-sm font-medium text-gray-700 mb-2">Settlement Method *</label>
                        <select id="settlement_method" name="settlement_method"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Select method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="paypal">PayPal</option>
                            <option value="stripe">Stripe</option>
                        </select>
                    </div>

                    <div>
                        <label for="settlement_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="settlement_notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Any additional information..."></textarea>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6">
                    <button type="button" id="cancel-settlement"
                            class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-all">
                        Cancel
                    </button>
                    <button type="submit" id="submit-settlement"
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('post-script')
<script src="{{ asset('assets/js/payment-gateway/payment-gateway.js') }}"></script>
<script>
// Copy to clipboard function
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(element.value).then(() => {
        // Show temporary success message
        const originalBtnText = event.target.innerHTML;
        event.target.innerHTML = '<i class="fa-solid fa-check"></i> Copied!';
        setTimeout(() => {
            event.target.innerHTML = originalBtnText;
        }, 2000);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Export dropdown functionality
    const exportDropdownBtn = document.getElementById('export-dropdown-btn');
    const exportDropdownMenu = document.getElementById('export-dropdown-menu');

    if (exportDropdownBtn && exportDropdownMenu) {
        exportDropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exportDropdownMenu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!exportDropdownBtn.contains(e.target) && !exportDropdownMenu.contains(e.target)) {
                exportDropdownMenu.classList.add('hidden');
            }
        });
    }

    // Settlement modal functionality
    const settlementBtn = document.getElementById('settlement-btn');
    const settlementModal = document.getElementById('settlement-modal');
    const closeModalBtn = document.getElementById('close-settlement-modal');
    const cancelBtn = document.getElementById('cancel-settlement');
    const settlementForm = document.getElementById('settlement-form');
    const availableBalanceSpan = document.getElementById('available-balance');
    const settlementAmountInput = document.getElementById('settlement_amount');

    const walletBalance = parseFloat(settlementBtn.dataset.walletBalance) || 0;

    // Set available balance
    availableBalanceSpan.textContent = '$' + walletBalance.toFixed(2);

    // Set max amount for settlement input
    settlementAmountInput.max = walletBalance;

    // Show modal
    settlementBtn.addEventListener('click', function() {
        if (walletBalance <= 0) {
            alert('No funds available for settlement.');
            return;
        }
        settlementModal.classList.remove('hidden');
    });

    // Hide modal
    function hideModal() {
        settlementModal.classList.add('hidden');
        settlementForm.reset();
    }

    closeModalBtn.addEventListener('click', hideModal);
    cancelBtn.addEventListener('click', hideModal);

    // Close modal when clicking outside
    settlementModal.addEventListener('click', function(e) {
        if (e.target === settlementModal) {
            hideModal();
        }
    });

    // Handle form submission
    settlementForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submit-settlement');
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
                alert('Settlement request submitted successfully! You will be contacted within 24 hours.');
                hideModal();
                // Optionally reload the page to update balances
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
});
</script>
@endsection
