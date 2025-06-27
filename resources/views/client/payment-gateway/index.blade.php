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
@endsection

@section('post-script')
<script src="{{ asset('assets/js/payment-gateway/payment-gateway.js') }}"></script>
@endsection
