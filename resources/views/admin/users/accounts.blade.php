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
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">User Accounts</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Bank accounts and crypto wallets for {{ $user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bank Accounts Section -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Bank Accounts</h2>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Account Details</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Bank Info</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($user->bankAccounts as $account)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $account->account_holder_name }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">{{ $account->account_number }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 dark:text-white">{{ $account->bank_name }}</div>
                                    <div class="text-sm text-slate-500 dark:text-slate-400">SWIFT: {{ $account->swift }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $account->account_type === 'Own' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400' }}">
                                        {{ $account->account_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $account->is_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                        {{ $account->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.users.bank-accounts.show', ['user' => $user->id, 'bankAccount' => $account->id]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 group">
                                        <i class="material-symbols-outlined text-lg mr-1">visibility</i>
                                        View Details
                                        <i class="material-symbols-outlined text-lg ml-1 transition-transform group-hover:translate-x-1">arrow_forward</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                    No bank accounts found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Crypto Wallets Section -->
    <div>
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white mb-4">Crypto Wallets</h2>
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                    <thead class="bg-slate-50 dark:bg-slate-700/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Currency</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Wallet Address</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Alias</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($user->cryptoWallets as $wallet)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($wallet->currency->icon)
                                            <img src="{{ asset('storage/' . $wallet->currency->icon) }}" alt="{{ $wallet->currency->name }}" 
                                                 class="h-6 w-6 rounded-full mr-2">
                                        @endif
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $wallet->currency->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-mono text-slate-900 dark:text-white">{{ $wallet->formattedAddress() }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 dark:text-white">{{ $wallet->alias }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $wallet->is_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                        {{ $wallet->is_verified ? 'Verified' : 'Pending' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.users.crypto-wallets.show', ['user' => $user->id, 'cryptoWallet' => $wallet->id]) }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300 group">
                                        <i class="material-symbols-outlined text-lg mr-1">visibility</i>
                                        View Details
                                        <i class="material-symbols-outlined text-lg ml-1 transition-transform group-hover:translate-x-1">arrow_forward</i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-slate-500 dark:text-slate-400">
                                    No crypto wallets found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 