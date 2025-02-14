@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl mx-auto">
        <!-- Success Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Success!</h1>
            <p class="mt-2 text-gray-600">Your exchange has been completed</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-100">
            <!-- Exchange Summary -->
            <div class="p-8 text-center border-b border-gray-100">
                <div class="flex justify-between items-center space-x-4">
                    <!-- From Amount -->
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">You Sent</p>
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($otcRequest->from_amount, 8) }}
                        </div>
                        <div class="text-lg font-semibold text-indigo-600">
                            {{ $otcRequest->fromCurrency->symbol }}
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </div>

                    <!-- To Amount -->
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-500 mb-1">You Received</p>
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($otcRequest->to_amount, 8) }}
                        </div>
                        <div class="text-lg font-semibold text-indigo-600">
                            {{ $otcRequest->toCurrency->symbol }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div class="px-8 py-6 bg-gray-50 space-y-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Transaction ID</span>
                    <span class="font-medium text-gray-900">{{ $otcRequest->id }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Exchange Rate</span>
                    <span class="font-medium text-gray-900">
                        1 {{ $otcRequest->fromCurrency->symbol }} = {{ number_format($otcRequest->exchange_rate, 8) }} {{ $otcRequest->toCurrency->symbol }}
                    </span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Network Fee</span>
                    <span class="font-medium text-gray-900">{{ number_format($otcRequest->network_fee, 8) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Completed
                    </span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('client.otc.index') }}"
               class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                New Exchange
            </a>
            <a href="{{route('client.activities.index')}}"
               class="inline-flex items-center px-5 py-2.5 border border-gray-200 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150">
                View History
            </a>
        </div>
    </div>
</div>
@endsection
