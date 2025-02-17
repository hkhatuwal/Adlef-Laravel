@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.transfers.index') }}"
               class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                <i class="material-symbols-outlined text-2xl">arrow_back</i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Transfer Details</h1>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Reference: {{ $transfer->reference_number }}</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            @if($transfer->status === 'pending')
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.transfers.verify', $transfer) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="material-symbols-outlined text-[20px] mr-2">verified</i>
                            Verify Payment
                        </button>
                    </form>
                    <form action="{{ route('admin.transfers.reject', $transfer) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="material-symbols-outlined text-[20px] mr-2">cancel</i>
                            Reject Transfer
                        </button>
                    </form>
                </div>
            @endif
            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                {{ $transfer->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' :
                   ($transfer->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' :
                   'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400') }}">
                <i class="material-symbols-outlined text-[20px] mr-2">
                    {{ $transfer->status === 'completed' ? 'check_circle' :
                       ($transfer->status === 'failed' ? 'error' : 'pending') }}
                </i>
                {{ ucfirst($transfer->status) }}
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
        <!-- Amount Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Amount</h3>
                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center">
                    <i class="material-symbols-outlined text-indigo-600 dark:text-indigo-400">payments</i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-2xl font-bold text-slate-900 dark:text-white">
                    {{ number_format($transfer->amount, 8) }} {{ $transfer->currency->symbol }}
                </div>
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    Fee: {{ number_format($transfer->fee, 8) }} {{ $transfer->currency->symbol }}
                </div>
            </div>
        </div>

        <!-- Type Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Transfer Type</h3>
                <div class="w-10 h-10 rounded-lg bg-purple-50 dark:bg-purple-900/50 flex items-center justify-center">
                    <i class="material-symbols-outlined text-purple-600 dark:text-purple-400">swap_horiz</i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    {{ $transfer->transfer_type === 'in' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' :
                       ($transfer->transfer_type === 'out' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' :
                       'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400') }}">
                    <i class="material-symbols-outlined text-[20px] mr-2">
                        {{ $transfer->transfer_type === 'in' ? 'arrow_downward' :
                           ($transfer->transfer_type === 'out' ? 'arrow_upward' : 'swap_horiz') }}
                    </i>
                    {{ ucfirst($transfer->transfer_type) }} Transfer
                </div>
            </div>
        </div>

        <!-- Date Card -->
        <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200 dark:border-slate-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Date & Time</h3>
                <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-900/50 flex items-center justify-center">
                    <i class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">calendar_today</i>
                </div>
            </div>
            <div class="space-y-1">
                <div class="text-lg font-semibold text-slate-900 dark:text-white">
                    {{ $transfer->created_at->format('M d, Y') }}
                </div>
                <div class="text-sm text-slate-500 dark:text-slate-400">
                    {{ $transfer->created_at->format('H:i:s') }} UTC
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
                            {{ strtoupper(substr($transfer->user->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $transfer->user->name }}
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ $transfer->user->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Information -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">Currency Information</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center">
                    @if($transfer->currency->icon)
                        <img src="{{ asset('storage/' . $transfer->currency->icon) }}"
                             alt="{{ $transfer->currency->name }}"
                             class="h-12 w-12 rounded-lg object-contain bg-slate-100 dark:bg-slate-700 p-1">
                    @endif
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ $transfer->currency->name }}
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            Symbol: {{ $transfer->currency->symbol }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Details -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <!-- From Account -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">From Account</h3>
            </div>
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <i class="material-symbols-outlined text-slate-500 dark:text-slate-400">
                            {{ $transfer->from_account_type === 'App\\Models\\BankAccount' ? 'account_balance' : 'account_balance_wallet' }}
                        </i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ class_basename($transfer->from_account_type) }}
                        </h4>
                        @if($transfer->from_account)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                                {{ $transfer->from_account->name ?? $transfer->from_account->bank_name ?? 'N/A' }}
                            </p>
                            @if($transfer->from_account_type === 'App\\Models\\BankAccount')
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    Account Number: {{ $transfer->from_account->account_number }}<br>
                                    SWIFT: {{ $transfer->from_account->swift }}
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-slate-500 dark:text-slate-400">Account details not available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- To Account -->
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-medium text-slate-900 dark:text-white">To Account</h3>
            </div>
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                        <i class="material-symbols-outlined text-slate-500 dark:text-slate-400">
                            {{ $transfer->to_account_type === 'App\\Models\\BankAccount' ? 'account_balance' : 'account_balance_wallet' }}
                        </i>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-medium text-slate-900 dark:text-white">
                            {{ class_basename($transfer->to_account_type) }}
                        </h4>
                        @if($transfer->to_account)
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">
                                {{ $transfer->to_account->name ?? $transfer->to_account->bank_name ?? 'N/A' }}
                            </p>
                            @if($transfer->to_account_type === 'App\\Models\\BankAccount')
                                <div class="text-sm text-slate-500 dark:text-slate-400">
                                    Account Number: {{ $transfer->to_account->account_number }}<br>
                                    SWIFT: {{ $transfer->to_account->swift }}
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-slate-500 dark:text-slate-400">Account details not available</p>
                        @endif
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
                    @foreach($transfer->activities as $activity)
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
@endsection
