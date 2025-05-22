@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.otc.index') }}"
               class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                <i class="material-symbols-outlined text-2xl">arrow_back</i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">OTC Trade Details</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Trade ID: {{ $otc->id }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            @if($otc->status === 'pending')
                <div class="flex items-center gap-2">
                    <button type="button" id="process_otc"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="material-symbols-outlined text-[20px] mr-2">play_arrow</i>
                        Process Trade
                    </button>
                    <button type="button" id="hold_otc"
                            class="inline-flex items-center px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="material-symbols-outlined text-[20px] mr-2">pause</i>
                        Hold Trade
                    </button>
                    <form action="{{ route('admin.otc.reject', $otc) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="material-symbols-outlined text-[20px] mr-2">cancel</i>
                            Reject Trade
                        </button>
                    </form>
                </div>
            @elseif($otc->status === 'on-hold')
                <div class="flex items-center gap-2">
                    <button type="button" id="process_otc"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="material-symbols-outlined text-[20px] mr-2">play_arrow</i>
                        Process Trade
                    </button>
                    <form action="{{ route('admin.otc.reject', $otc) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="material-symbols-outlined text-[20px] mr-2">cancel</i>
                            Reject Trade
                        </button>
                    </form>
                </div>
            @elseif($otc->status === 'processing')
                <form action="{{ route('admin.otc.complete', $otc) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="material-symbols-outlined text-[20px] mr-2">check_circle</i>
                        Complete Trade
                    </button>
                </form>
            @endif
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                {{ $otc->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' :
                   ($otc->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' :
                   ($otc->status === 'processing' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400' :
                   ($otc->status === 'on-hold' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-400' :
                   'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400'))) }}">
                <i class="material-symbols-outlined text-[20px] mr-2">
                    {{ $otc->status === 'completed' ? 'check_circle' :
                       ($otc->status === 'failed' ? 'error' :
                       ($otc->status === 'processing' ? 'sync' :
                       ($otc->status === 'on-hold' ? 'pause' : 'pending'))) }}
                </i>
                {{ $otc->status === 'on-hold' ? 'On Hold' : ucfirst($otc->status) }}
            </span>
        </div>
    </div>

    <!-- Action Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-emerald-500 mr-2">check_circle</i>
                <p class="text-emerald-600 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-red-500 mr-2">error</i>
                <p class="text-red-600 dark:text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6 mb-6">
        <!-- From Amount Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">From Amount</h3>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center">
                    <i class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">payments</i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ number_format($otc->from_amount, 8) }} {{ $otc->fromCurrency->symbol }}
                </div>
            </div>
        </div>

        <!-- To Amount Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">To Amount</h3>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/50 flex items-center justify-center">
                    <i class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">payments</i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ number_format($otc->to_amount, 8) }} {{ $otc->toCurrency->symbol }}
                </div>
            </div>
        </div>

        <!-- Exchange Rate Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Exchange Rate</h3>
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/50 flex items-center justify-center">
                    <i class="material-symbols-outlined text-purple-600 dark:text-purple-400">currency_exchange</i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ number_format($otc->exchange_rate, 4) }}
                </div>
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    1 {{ $otc->fromCurrency->symbol }} = {{ number_format($otc->exchange_rate, 4) }} {{ $otc->toCurrency->symbol }}
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- User Information -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">User Information</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                        <span class="text-lg font-medium text-indigo-600 dark:text-indigo-400">
                            {{ strtoupper(substr($otc->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $otc->user->name }}
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $otc->user->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fee Information -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">Fee Information</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Network Fee</span>
                        <span class="text-sm font-medium text-slate-900 dark:text-white">
                            {{ number_format($otc->network_fee, 8) }} {{ $otc->fromCurrency->symbol }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-500 dark:text-slate-400">Total Amount (incl. fees)</span>
                        <span class="text-base font-semibold text-slate-900 dark:text-white">
                            {{ number_format($otc->from_amount + $otc->network_fee, 8) }} {{ $otc->fromCurrency->symbol }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity History -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-medium text-slate-900 dark:text-white">Activity History</h3>
        </div>
        <div class="p-6">
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($otc->activities as $activity)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200 dark:bg-slate-700" aria-hidden="true"></span>
                                @endif
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-slate-800
                                            {{ $activity->status === 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/50' :
                                               ($activity->status === 'failed' ? 'bg-red-100 dark:bg-red-900/50' :
                                               'bg-blue-100 dark:bg-blue-900/50') }}">
                                            <i class="material-symbols-outlined text-lg
                                                {{ $activity->status === 'completed' ? 'text-emerald-600 dark:text-emerald-400' :
                                                   ($activity->status === 'failed' ? 'text-red-600 dark:text-red-400' :
                                                   'text-blue-600 dark:text-blue-400') }}">
                                                {{ $activity->status === 'completed' ? 'check_circle' :
                                                   ($activity->status === 'failed' ? 'error' : 'pending') }}
                                            </i>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $activity->description }}
                                        </div>
                                        <div class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                            {{ $activity->created_at->format('M d, Y H:i:s') }}
                                        </div>
                                        @if($activity->status === 'failed' && $otc->failure_reason)
                                            <div class="mt-2 text-sm text-red-600 dark:text-red-400">
                                                Reason: {{ $otc->failure_reason }}
                                            </div>
                                        @endif
                                        @if($activity->status === 'on-hold' && $otc->hold_reason)
                                            <div class="mt-2 text-sm text-orange-600 dark:text-orange-400">
                                                Reason: {{ $otc->hold_reason }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Network Fee Modal -->
<div id="networkFeeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-2xl w-full">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                            Process OTC Trade
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Adjust network fee before processing
                        </p>
                    </div>
                    <button type="button"
                            id="close-network-fee-modal"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <i class="material-symbols-outlined text-2xl">close</i>
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.otc.process', $otc) }}" method="POST" id="processFeeForm">
                @csrf
                <div class="p-6">
                    <!-- Fee Adjustment -->
                    <div class="mb-8">
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div class="relative">
                                <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fee Percentage</h4>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400">%</span>
                                    </div>
                                    <input type="number"
                                           id="networkFeePercentage"
                                           step="0.01"
                                           min="0"
                                           value="{{ ($otc->network_fee / $otc->from_amount) * 100 }}"
                                           class="block w-full rounded-lg border-slate-200 pr-8 py-2 text-right bg-white dark:bg-slate-700 dark:border-slate-600 focus:border-indigo-500 focus:ring-indigo-500 dark:text-white text-sm font-medium">
                                </div>
                            </div>
                            <div class="relative">
                                <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Fee Amount</h4>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400">{{ $otc->fromCurrency->symbol }}</span>
                                    </div>
                                    <input type="number"
                                           id="networkFeeAmount"
                                           name="network_fee"
                                           value="{{ $otc->network_fee }}"
                                           step="0.00000001"
                                           min="0"
                                           data-amount="{{ $otc->from_amount }}"
                                           data-currency="{{ $otc->fromCurrency->symbol }}"
                                           class="block w-full rounded-lg border-slate-200 !pr-12 py-2 text-right bg-white dark:bg-slate-700 dark:border-slate-600 focus:border-indigo-500 focus:ring-indigo-500 dark:text-white text-sm font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Transaction Cost -->
                        <div class="grid grid-cols-2 gap-6 mb-6">
                            <div class="relative">
                                <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Transaction Cost Percentage</h4>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400">%</span>
                                    </div>
                                    <input type="number"
                                           id="transactionCostPercentage"
                                           step="0.01"
                                           min="0"
                                           value="{{ ($otc->transaction_cost / $otc->from_amount) * 100 }}"
                                           class="block w-full rounded-lg border-slate-200 pr-8 py-2 text-right bg-white dark:bg-slate-700 dark:border-slate-600 focus:border-indigo-500 focus:ring-indigo-500 dark:text-white text-sm font-medium">
                                </div>
                            </div>
                            <div class="relative">
                                <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Transaction Cost Amount</h4>
                                <div class="relative">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-slate-500 dark:text-slate-400">{{ $otc->fromCurrency->symbol }}</span>
                                    </div>
                                    <input type="number"
                                           id="transactionCostAmount"
                                           name="transaction_cost"
                                           value="{{ $otc->transaction_cost }}"
                                           step="0.00000001"
                                           min="0"
                                           data-amount="{{ $otc->from_amount }}"
                                           data-currency="{{ $otc->fromCurrency->symbol }}"
                                           class="block w-full rounded-lg border-slate-200 !pr-12 py-2 text-right bg-white dark:bg-slate-700 dark:border-slate-600 focus:border-indigo-500 focus:ring-indigo-500 dark:text-white text-sm font-medium">
                                </div>
                            </div>
                        </div>

                        <!-- Quick Presets -->
                        <div class="flex gap-2 mb-6">
                            <button type="button"
                                    onclick="setNetworkFeePreset(0.1)"
                                    class="px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">
                                0.1%
                            </button>
                            <button type="button"
                                    onclick="setNetworkFeePreset(0.5)"
                                    class="px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">
                                0.5%
                            </button>
                            <button type="button"
                                    onclick="setNetworkFeePreset(1.0)"
                                    class="px-3 py-1 text-xs font-medium rounded-full bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">
                                1.0%
                            </button>
                        </div>

                        <!-- Calculations Display -->
                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700/50 rounded-xl p-6">
                            <div class="grid grid-cols-3 gap-6 mb-6">
                                <div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">Initial Amount</div>
                                    <div class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                                        {{ number_format($otc->from_amount, 8) }}
                                        <span class="text-sm font-medium text-slate-500">{{ $otc->fromCurrency->symbol }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">Network Fee</div>
                                    <div id="displayNetworkFee" class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                                        {{ number_format($otc->network_fee, 8) }}
                                        <span class="text-sm font-medium text-slate-500">{{ $otc->fromCurrency->symbol }}</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">Transaction Cost</div>
                                    <div id="displayTransactionCost" class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                                        {{ number_format($otc->transaction_cost, 8) }}
                                        <span class="text-sm font-medium text-slate-500">{{ $otc->fromCurrency->symbol }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="border-t border-slate-200 dark:border-slate-600 pt-4">
                                <div class="flex justify-between items-baseline">
                                    <div class="text-sm font-medium text-slate-500 dark:text-slate-400">Final Amount</div>
                                    <div id="displayFinalAmount" class="text-xl font-bold text-emerald-600 dark:text-emerald-400">
                                        {{ number_format($otc->from_amount + $otc->network_fee + $otc->transaction_cost, 8) }}
                                        <span class="text-base font-medium ml-1">{{ $otc->fromCurrency->symbol }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Warning Message -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="material-symbols-outlined text-amber-500">warning</i>
                            <div class="text-sm text-amber-800 dark:text-amber-300">
                                Please review all details carefully. This action will process the OTC trade with the specified network fee.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-200 dark:border-slate-700 rounded-b-xl flex justify-end gap-3">
                    <button type="button"
                            id="cancel-network-fee"
                            class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center gap-2">
                        <i class="material-symbols-outlined text-[20px]">play_arrow</i>
                        Process Trade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hold Modal -->
<div id="holdModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl max-w-lg w-full">
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-slate-900 dark:text-white">
                            Hold OTC Trade
                        </h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Provide a reason for holding this trade
                        </p>
                    </div>
                    <button type="button"
                            id="close-hold-modal"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                        <i class="material-symbols-outlined text-2xl">close</i>
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.otc.hold', $otc) }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="mb-6">
                        <label for="hold_reason" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Reason for Hold
                        </label>
                        <textarea
                            id="hold_reason"
                            name="reason"
                            rows="4"
                            class="block w-full rounded-lg border-slate-200 bg-white dark:bg-slate-700 dark:border-slate-600 focus:border-indigo-500 focus:ring-indigo-500 dark:text-white text-sm"
                            placeholder="Explain why this trade is being put on hold..."
                            required
                        ></textarea>
                    </div>

                    <!-- Warning Message -->
                    <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-lg p-4">
                        <div class="flex gap-3">
                            <i class="material-symbols-outlined text-amber-500">warning</i>
                            <div class="text-sm text-amber-800 dark:text-amber-300">
                                This will place the OTC trade on hold and notify the customer. You can process or reject the trade later.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700/30 border-t border-slate-200 dark:border-slate-700 rounded-b-xl flex justify-end gap-3">
                    <button type="button"
                            id="cancel-hold"
                            class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-colors duration-200 inline-flex items-center gap-2">
                        <i class="material-symbols-outlined text-[20px]">pause</i>
                        Hold Trade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('admin/js/script.js') }}"></script>
@endpush
@endsection
