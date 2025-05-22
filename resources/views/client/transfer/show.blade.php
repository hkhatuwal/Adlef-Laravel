@extends('client._layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-100 to-white py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header with Back Button -->
            <div class="flex items-center mb-6">
                <a href="{{ route('client.transfer') }}"
                   class="group inline-flex items-center text-slate-700 hover:text-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-medium">Back to Transfers</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Card - Transfer Information -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-slate-200">
                    <!-- Header -->
                    <div class="px-6 py-5 bg-gradient-to-r from-blue-700 to-indigo-800">
                        <div class="flex justify-between items-center">
                            <div>
                                <h1 class="text-2xl font-bold text-white tracking-tight">Asset Transfer</h1>
                                <p class="mt-1 text-sm text-blue-200">Transaction
                                    ID: {{ $transfer->reference_number }}</p>
                            </div>

                            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{
                            $transfer->status === 'completed' ? 'bg-emerald-500 text-white' :
                            ($transfer->status === 'pending' ? 'bg-amber-500 text-white' :
                            ($transfer->status === 'failed' ? 'bg-red-500 text-white' :
                            ($transfer->status === 'hold' ? 'bg-orange-500 text-white' :
                            ($transfer->status === 'cancelled' ? 'bg-slate-500 text-white' :
                            'bg-blue-500 text-white'))))
                             }}">
                                <i class="fa-solid {{
                                $transfer->status === 'completed' ? 'fa-circle-check' :
                                ($transfer->status === 'failed' ? 'fa-circle-xmark' :
                                ($transfer->status === 'hold' ? 'fa-pause-circle' :
                                ($transfer->status === 'cancelled' ? 'fa-ban' :
                                'fa-clock')))
                                 }} mr-2"></i>
                                {{ ucfirst($transfer->status) }}
                            </div>
                        </div>
                    </div>

                    <!-- Transfer Amount -->
                    <div class="px-6 py-8 text-center bg-white">
                        <div class="mb-2 text-sm font-medium text-slate-600 tracking-wide">TRANSFER AMOUNT</div>
                        <div class="text-5xl font-bold text-slate-900 mb-4">
                            {{ number_format($transfer->amount, 8) }}
                            <span class="text-lg text-slate-600 ml-2">{{ $transfer->currency->symbol }}</span>
                        </div>

                        <!-- Transfer Details -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between px-8">
                                <div class="text-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="text-lg font-semibold text-slate-900">From Account</div>
                                    <div class="mt-1 text-sm text-slate-500">
                                        @php
                                            $fromAccount = $transfer->from_account;
                                            $fromAccountType = class_basename($fromAccount);
                                        @endphp

                                        @if($fromAccountType === 'BankAccount')
                                            {{ $fromAccount->bank_name }}
                                            <br><span class="text-blue-400">
                                            #{{ $fromAccount->account_number }}
                                        </span>
                                        @elseif($fromAccountType === 'CryptoWallet')
                                            {{ $fromAccount->currency->name }} Wallet
                                            <br><span class="text-blue-400" title="{{ $fromAccount->wallet_address }}">
                                            {{ $fromAccount->formattedAddress() }}
                                        </span>
                                        @elseif($fromAccountType === 'AssetAccount')
                                            {{ $fromAccount->name }}
                                            <br><span class="text-blue-400">
                                            #{{ $fromAccount->account_number }}
                                        </span>
                                        @else
                                            <span class="text-blue-400">
                                            YOUR CRYPTO ACCOUNT
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="relative px-4">
                                    <svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <div class="text-center p-4 bg-slate-50 rounded-lg border border-slate-200">
                                    <div class="text-lg font-semibold text-slate-900">To Account</div>
                                    <div class="mt-1 text-sm text-slate-500">
                                        @php
                                            $toAccount = $transfer->to_account;
                                            $toAccountType = class_basename($toAccount);
                                        @endphp

                                        @if($toAccountType === 'BankAccount')
                                            {{ $toAccount->bank_name }}
                                            <br><span class="text-blue-400">
                                            #{{ $toAccount->account_number }}
                                        </span>
                                        @elseif($toAccountType === 'CryptoWallet')
                                            {{ $toAccount->currency->name }} Wallet
                                            <br><span class="text-blue-400" title="{{ $toAccount->wallet_address }}">
                                            {{ $toAccount->formattedAddress() }}
                                        </span>
                                        @elseif($toAccountType === 'AssetAccount')
                                            {{ $toAccount->name }}
                                            <br><span class="text-blue-400">
                                            #{{ $toAccount->account_number }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($transfer->fee > 0)
                            <!-- Fee Information -->
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-blue-700">Transfer Fee</span>
                                    <span
                                        class="text-sm font-medium text-blue-900">{{ number_format($transfer->fee, 8) }} {{ $transfer->currency->symbol }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Card - Transaction Details -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-slate-200">
                    <div class="px-6 py-5 bg-gradient-to-r from-slate-800 to-slate-900">
                        <h2 class="text-xl font-bold text-white">Transaction Details</h2>
                        <p class="mt-1 text-sm text-slate-300">{{ $transfer->created_at->format('M d Y, H:i A') }}</p>
                    </div>

                    <div class="divide-y divide-slate-200">
                        <!-- Transfer Type -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Transfer Type</span>
                                <span class="text-sm font-medium text-slate-900">
                                {{ ucfirst($transfer->transfer_type) }}
                            </span>
                            </div>
                        </div>

                        <!-- Currency -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Currency</span>
                                <span class="text-sm font-medium text-slate-900">
                                {{ $transfer->currency->name }} ({{ $transfer->currency->symbol }})
                            </span>
                            </div>
                        </div>

                        <!-- From Account Details -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">From Account</span>
                                <span class="text-sm font-medium text-slate-900">
                                @php
                                    $fromAccount = $transfer->from_account;
                                    $fromAccountType = class_basename($fromAccount);
                                @endphp

                                    @if($fromAccountType === 'BankAccount')
                                        {{ $fromAccount->bank_name }} - {{ $fromAccount->account_number }}
                                        @if($fromAccount->account_type)
                                            <br><span
                                                class="text-sm text-slate-500">Type: {{ $fromAccount->account_type }}</span>
                                        @endif
                                    @elseif($fromAccountType === 'CryptoWallet')
                                        {{ $fromAccount->currency->name }} Wallet
                                        <br><span class="text-sm" title="{{ $fromAccount->wallet_address }}">
                                        {{ $fromAccount->formattedAddress() }}
                                    </span>
                                    @elseif($fromAccountType === 'AssetAccount')
                                        {{ $fromAccount->name }} ({{ $fromAccount->currency->symbol }})
                                        <br><span class="text-sm">#{{ $fromAccount->account_number }}</span>

                                    @endif

                            </span>
                            </div>
                        </div>

                        <!-- To Account Details -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">To Account</span>
                                <span class="text-sm font-medium text-slate-900">
                                @php
                                    $toAccount = $transfer->to_account;
                                    $toAccountType = class_basename($toAccount);
                                @endphp

                                    @if($toAccountType === 'BankAccount')
                                        {{ $toAccount->bank_name }} - {{ $toAccount->account_number }}
                                        @if($toAccount->account_type)
                                            <br><span
                                                class="text-sm text-slate-500">Type: {{ $toAccount->account_type }}</span>
                                        @endif
                                    @elseif($toAccountType === 'CryptoWallet')
                                        {{ $toAccount->currency->name }} Wallet
                                        <br><span class="text-sm" title="{{ $toAccount->wallet_address }}">
                                        {{ $toAccount->formattedAddress() }}
                                    </span>
                                    @elseif($toAccountType === 'AssetAccount')
                                        {{ $toAccount->name }} ({{ $toAccount->currency->symbol }})
                                        <br><span class="text-sm">#{{ $toAccount->account_number }}</span>
                                    @endif
                            </span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Status</span>
                                <span class="text-sm font-medium {{
                                $transfer->status === 'completed' ? 'text-emerald-600' :
                                ($transfer->status === 'pending' ? 'text-amber-600' :
                                ($transfer->status === 'failed' ? 'text-red-600' :
                                ($transfer->status === 'hold' ? 'text-orange-600' :
                                'text-slate-600'))) }}">
                                {{ ucfirst($transfer->status) }}
                            </span>
                            </div>
                        </div>

                        <!-- Reference Number -->
                        <div class="px-6 py-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-slate-600">Reference No.</span>
                                <span
                                    class="text-sm font-medium text-slate-900">{{ $transfer->reference_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
