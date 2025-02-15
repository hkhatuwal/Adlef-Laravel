@extends('client._layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-100 to-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Back Button -->
            <div class="flex items-center mb-6">
                <a href="{{ route('client.otc.index') }}"
                   class="group inline-flex items-center text-slate-700 hover:text-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-medium">Back to Transactions</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Card - Amount Information -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-slate-200">
                    <!-- Header -->
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-700 to-indigo-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold text-white tracking-tight">Currency Exchange</h1>
                                <p class="mt-1 text-sm text-blue-200">Transaction
                                    ID: {{ $otcRequest->reference_number }}</p>
                            </div>

                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{
                            $otcRequest->status === 'completed' ? 'bg-emerald-500 text-white' :
                            ($otcRequest->status === 'pending' ? 'bg-amber-500 text-white' :
                            ($otcRequest->status === 'failed' ? 'bg-red-500 text-white' :
                            ($otcRequest->status === 'cancelled' ? 'bg-slate-500 text-white' :
                            'bg-blue-500 text-white'))) }}">
                                <i class="fa-solid {{
                                $otcRequest->status === 'completed' ? 'fa-circle-check' :
                                ($otcRequest->status === 'failed' ? 'fa-circle-xmark' :
                                ($otcRequest->status === 'cancelled' ? 'fa-ban' :
                                'fa-clock')) }} mr-2"></i>
                                {{ ucfirst($otcRequest->status) }}
                            </div>
                        </div>
                    </div>

                    <!-- Exchange Amount -->
                    <div class="px-6 py-8 text-center bg-white">
                        <div class="mb-2 text-sm font-medium text-slate-600 tracking-wide">TOTAL AMOUNT</div>
                        <div class="text-5xl font-bold text-slate-900 mb-4">
                            <span class="text-green-500">${{ number_format($otcRequest->from_amount * $otcRequest->exchange_rate, 2) }}</span>
                            <span class="text-lg text-green-600 ml-2">USD</span>
                        </div>

                        <!-- Exchange Details -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between px-8">
                                <div class="text-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="text-2xl font-semibold text-slate-900">
                                        {{ number_format($otcRequest->from_amount, 8) }}
                                        <span class="text-lg text-slate-600">{{ $otcRequest->fromCurrency->symbol }}</span>
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $otcRequest->fromCurrency->name }}</div>
                                </div>
                                <div class="relative px-4">
                                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <div class="text-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="text-2xl font-semibold text-slate-900">
                                        {{ number_format($otcRequest->to_amount, 8) }}
                                        <span class="text-lg text-slate-600">{{ $otcRequest->toCurrency->symbol }}</span>
                                    </div>
                                    <div class="mt-1 text-sm text-slate-500">{{ $otcRequest->toCurrency->name }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Exchange Rate -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="text-sm text-slate-700">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-7.536 5.879a1 1 0 001.415 0 3 3 0 014.242 0 1 1 0 001.415-1.415 5 5 0 00-7.072 0 1 1 0 000 1.415z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <span class="mr-2">Exchange rate:</span>
                                <span class="font-medium text-slate-900">1 {{ $otcRequest->fromCurrency->symbol }} ≈ {{ number_format($otcRequest->exchange_rate, 4) }} USD</span>
                            </span>
                            </div>
                        </div>
                    </div>

                    <!-- Fees Section -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-slate-500 mr-2" fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-slate-700">FDT Fee</span>
                            </div>
                            <span class="text-sm font-semibold text-slate-900">{{ number_format($otcRequest->network_fee, 8) }} USD</span>
                        </div>
                    </div>
                </div>

                <!-- Right Card - Account Details -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-slate-200">
                    <div class="px-6 py-5 bg-gradient-to-r from-slate-800 to-slate-900">
                        <h2 class="text-xl font-bold text-white">Transaction Details</h2>
                        <p class="mt-1 text-sm text-slate-300">{{ $otcRequest->created_at->format('M d Y, H:i A') }}</p>
                    </div>

                    <!-- From Account -->
                    <div class="px-6 py-6 border-b border-slate-200">
                        <h3 class="flex items-center text-sm font-medium text-slate-600 mb-4">
                            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            FROM MY FDT ACCOUNT
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Client</span>
                                <span class="text-slate-900 font-medium">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Service Account No.</span>
                                <span class="text-slate-900 font-medium">{{ $otcRequest->fromCurrency->getMyAssetAccount()?->account_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- To Account -->
                    <div class="px-6 py-6">
                        <h3 class="flex items-center text-sm font-medium text-slate-600 mb-4">
                            <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            TO MY FDT ACCOUNT
                        </h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Wallet Currency</span>
                                <span class="text-slate-900 font-medium">{{ $otcRequest->toCurrency->name }} ({{ $otcRequest->toCurrency->symbol }})</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-slate-600">Wallet address</span>
                                <div class="flex items-center">
                                    <span class="text-slate-900 font-medium">{{ $otcRequest->toCurrency->getMyAssetAccount()?->wallet_address }}</span>
                                    <button class="ml-2 text-blue-700 hover:text-blue-800 transition-colors"
                                            onclick="copyToClipboard('{{ $otcRequest->toCurrency->getMyAssetAccount()?->wallet_address }}')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Network</span>
                                <span class="text-slate-900 font-medium">{{ $otcRequest->toCurrency->network }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Client</span>
                                <span class="text-slate-900 font-medium">{{ auth()->user()->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Service Account No.</span>
                                <span class="text-slate-900 font-medium">{{ $otcRequest->toCurrency->getMyAssetAccount()?->account_number }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Number -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-slate-600">Reference No.</span>
                            <span class="text-sm font-medium text-slate-900">{{ $otcRequest->reference_number }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
