@extends('layouts.oppwa')

@section('title', 'Payment Pending')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Pending Icon -->
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                <svg class="h-8 w-8 text-yellow-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-yellow-600 mb-2">Payment Pending</h1>
            <p class="text-gray-600">Your payment is being processed. Please wait...</p>
        </div>

        <!-- Transaction Details -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Transaction Details</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Transaction ID:</span>
                    <span class="font-mono text-sm">{{ $transaction->transaction_id }}</span>
                </div>
                @if($transaction->external_order_id)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Order ID:</span>
                    <span class="font-mono text-sm">{{ $transaction->external_order_id }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Amount:</span>
                    <span class="font-semibold">{{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Created:</span>
                    <span class="text-sm">{{ $transaction->created_at->format('M d, Y H:i:s') }}</span>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">What's happening?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Your payment is being verified by the payment processor</li>
                <li>• This process usually takes a few minutes</li>
                <li>• You will be notified once the payment is confirmed</li>
                <li>• This page will automatically refresh to show the latest status</li>
            </ul>
        </div>

        <!-- Auto-refresh Status -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center px-4 py-2 bg-gray-100 rounded-lg">
                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                <span class="text-sm text-gray-600">Checking payment status...</span>
            </div>
        </div>

        <!-- Manual Refresh -->
        <div class="text-center">
            <button onclick="checkPaymentStatus()" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                Refresh Status
            </button>
        </div>
    </div>
</div>

<script>
let refreshInterval;

function checkPaymentStatus() {
    fetch(`{{ route('oppwa.result', ['transactionId' => $transaction->transaction_id]) }}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        }
    })
    .catch(error => {
        console.error('Error checking payment status:', error);
    });
}

// Auto-refresh every 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    refreshInterval = setInterval(checkPaymentStatus, 5000);
    
    // Stop refreshing after 5 minutes
    setTimeout(() => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    }, 300000);
});

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
    }
});
</script>
@endsection
