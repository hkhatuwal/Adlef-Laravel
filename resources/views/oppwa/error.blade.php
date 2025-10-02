@extends('layouts.oppwa')

@section('title', 'Payment Error')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Error Icon -->
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-red-600 mb-2">{{ $error ?? 'Payment Error' }}</h1>
            <p class="text-gray-600">{{ $message ?? 'An error occurred while processing your payment.' }}</p>
        </div>

        <!-- Transaction Details (if available) -->
        @if(isset($transaction) && $transaction)
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
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        {{ ucfirst($transaction->status) }}
                    </span>
                </div>
                @if($transaction->failure_reason)
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Failure Reason:</span>
                    <span class="text-sm text-red-600">{{ $transaction->failure_reason }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Error Details -->
        @if(isset($transaction) && $transaction && $transaction->oppwa_response)
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Error Details</h2>
            <div class="space-y-2">
                @if(isset($transaction->oppwa_response['result']))
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Error Code:</span>
                    <span class="font-mono text-sm">{{ $transaction->oppwa_response['result']['code'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Error Message:</span>
                    <span class="text-sm">{{ $transaction->oppwa_response['result']['description'] ?? 'N/A' }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Help Information -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-semibold text-blue-800 mb-2">Need Help?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Check your payment details and try again</li>
                <li>• Ensure your card has sufficient funds</li>
                <li>• Contact your bank if the issue persists</li>
                <li>• Contact support if you need assistance</li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex justify-center space-x-4">

            <a href="{{ route('frontend.home') }}"
               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                Back to Home
            </a>
        </div>

        <!-- Support Contact -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                If you continue to experience issues, please contact our support team.
            </p>
        </div>
    </div>
</div>
@endsection
