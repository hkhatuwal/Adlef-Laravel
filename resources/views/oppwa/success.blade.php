@extends('layouts.oppwa')

@section('title', 'Payment Successful')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Success Icon -->
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-green-600 mb-2">Payment Successful!</h1>
            <p class="text-gray-600">Your payment has been processed successfully.</p>
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
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Payment Date:</span>
                    <span class="text-sm">{{ $transaction->updated_at->format('M d, Y H:i:s') }}</span>
                </div>
                @if($transaction->oppwa_payment_id)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">OPPWA Payment ID:</span>
                    <span class="font-mono text-sm">{{ $transaction->oppwa_payment_id }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Payment Method Details -->
        @if($transaction->oppwa_response && isset($transaction->oppwa_response['paymentBrand']))
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Payment Method</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Card Type:</span>
                    <span class="text-sm">{{ ucfirst($transaction->oppwa_response['paymentBrand']) }}</span>
                </div>
                @if(isset($transaction->oppwa_response['card']['bin']))
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Card Number:</span>
                    <span class="font-mono text-sm">**** **** **** {{ $transaction->oppwa_response['card']['bin'] }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- OPPWA Response Details -->
        @if($transaction->oppwa_response && isset($transaction->oppwa_response['result']))
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Payment Response</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Response Code:</span>
                    <span class="font-mono text-sm">{{ $transaction->oppwa_response['result']['code'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Description:</span>
                    <span class="text-sm">{{ $transaction->oppwa_response['result']['description'] }}</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-center space-x-4">
            <a href="{{ route('frontend.home') }}" 
               class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                Back to Home
            </a>
            @if($transaction->external_order_id)
            <a href="#" 
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                View Order
            </a>
            @endif
        </div>

        <!-- Receipt Download -->
        <div class="mt-6 text-center">
            <button onclick="window.print()" 
                    class="text-sm text-blue-600 hover:text-blue-800 underline">
                Download Receipt
            </button>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
@endsection
