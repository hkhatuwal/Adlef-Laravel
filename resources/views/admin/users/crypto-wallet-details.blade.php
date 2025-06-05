@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.accounts', $user) }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-white dark:bg-slate-800 text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 transition-all hover:scale-105">
                    <i class="material-symbols-outlined text-2xl">arrow_back</i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Crypto Wallet Details</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Viewing crypto wallet information for {{ $user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/50 border border-emerald-200 dark:border-emerald-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-emerald-500 mr-2">check_circle</i>
                <p class="text-emerald-600 dark:text-emerald-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <!-- Main Wallet Information -->
        <div class="col-span-2 space-y-6">
            <!-- Wallet Overview -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">account_balance_wallet</i>
                        Wallet Overview
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Currency</label>
                            <div class="mt-1 flex items-center gap-3">
                                @if($cryptoWallet->currency->logo)
                                    <img src="{{ asset('storage/' . $cryptoWallet->currency->logo) }}" 
                                         alt="{{ $cryptoWallet->currency->name }}" 
                                         class="w-8 h-8 rounded-full">
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $cryptoWallet->currency->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $cryptoWallet->currency->symbol }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Wallet Address</label>
                            <div class="mt-1 bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                                <p class="text-sm font-mono text-slate-900 dark:text-white break-all">{{ $cryptoWallet->wallet_address }}</p>
                                <button onclick="copyToClipboard('{{ $cryptoWallet->wallet_address }}')" 
                                        class="mt-2 inline-flex items-center text-xs text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <i class="material-symbols-outlined text-sm mr-1">content_copy</i>
                                    Copy Address
                                </button>
                            </div>
                        </div>

                        @if($cryptoWallet->alias)
                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Alias</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-white">{{ $cryptoWallet->alias }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-500 dark:text-slate-400">Formatted Address (Preview)</label>
                            <p class="mt-1 text-sm font-mono text-slate-500 dark:text-slate-400">{{ $cryptoWallet->formattedAddress() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wallet Owner Information -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">person</i>
                        Wallet Owner
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-lg">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->email }}</p>
                            <a href="{{ route('admin.users.show', $user) }}" 
                               class="text-xs text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                View Profile →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status and Actions -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">verified</i>
                        Verification Status
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Wallet Status</span>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium
                                {{ $cryptoWallet->is_verified ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' }}">
                                <i class="material-symbols-outlined text-lg mr-2">
                                    {{ $cryptoWallet->is_verified ? 'verified' : 'pending' }}
                                </i>
                                {{ $cryptoWallet->is_verified ? 'Verified' : 'Pending Verification' }}
                            </span>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.verify-crypto-wallet', $cryptoWallet) }}" method="POST" class="mt-4">
                        @csrf
                        @method($cryptoWallet->is_verified ? 'DELETE' : 'POST')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg text-sm font-medium transition-all shadow-md
                                {{ $cryptoWallet->is_verified 
                                    ? 'bg-red-500 text-white hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800' 
                                    : 'bg-emerald-500 text-white hover:bg-emerald-600 dark:bg-emerald-700 dark:hover:bg-emerald-800' }}">
                            <i class="material-symbols-outlined text-lg mr-2">
                                {{ $cryptoWallet->is_verified ? 'cancel' : 'check_circle' }}
                            </i>
                            {{ $cryptoWallet->is_verified ? 'Revoke Verification' : 'Verify Wallet' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Currency Info Card -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                        <i class="material-symbols-outlined mr-2">currency_bitcoin</i>
                        Currency Details
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($cryptoWallet->currency->logo)
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/' . $cryptoWallet->currency->logo) }}" 
                                 alt="{{ $cryptoWallet->currency->name }}" 
                                 class="w-16 h-16 rounded-full">
                        </div>
                    @endif
                    <div class="text-center">
                        <p class="text-lg font-semibold text-slate-900 dark:text-white">{{ $cryptoWallet->currency->name }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-mono">{{ $cryptoWallet->currency->symbol }}</p>
                    </div>
                    @if($cryptoWallet->currency->description)
                        <div class="text-xs text-slate-600 dark:text-slate-400 text-center">
                            {{ $cryptoWallet->currency->description }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Created At -->
            <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Added On</span>
                        <span class="text-sm text-slate-900 dark:text-white">{{ $cryptoWallet->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Last Updated</span>
                        <span class="text-sm text-slate-900 dark:text-white">{{ $cryptoWallet->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // You could add a toast notification here
        console.log('Address copied to clipboard');
    }, function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endsection 