@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                    <i class="material-symbols-outlined text-2xl">arrow_back</i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Asset Accounts</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Asset accounts and balances for {{ $user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Accounts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($user->assetAccounts as $account)
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            @if($account->currency->icon)
                                <img src="{{ asset('storage/' . $account->currency->icon) }}" 
                                     alt="{{ $account->currency->name }}" 
                                     class="h-10 w-10 rounded-xl mr-3">
                            @endif
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $account->name }}</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $account->currency->name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Account Number</label>
                            <p class="mt-1 text-sm font-mono text-slate-900 dark:text-white">{{ $account->account_number }}</p>
                        </div>
                        
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Balance</label>
                            <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white">
                                {{ number_format($account->balance, 2) }} {{ $account->currency->symbol }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                        <i class="material-symbols-outlined text-3xl text-slate-400">account_balance_wallet</i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">No Asset Accounts</h3>
                    <p class="text-slate-500 dark:text-slate-400">This user has no asset accounts yet.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection 