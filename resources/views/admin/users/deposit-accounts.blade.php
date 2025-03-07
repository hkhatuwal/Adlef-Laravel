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
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Manage Deposit Accounts</h1>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Assign deposit accounts to {{ $user->name }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Deposit Accounts -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700 mb-8">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                <i class="material-symbols-outlined mr-2">account_balance</i>
                Current Deposit Accounts
            </h3>
        </div>
        <div class="p-8">
            @if($user->adminDepositAccounts->isNotEmpty())
                <div class="grid grid-cols-1 gap-4">
                    @foreach($user->adminDepositAccounts as $account)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                            <div>
                                <h4 class="text-sm font-medium text-slate-900 dark:text-white">{{ $account->name }}</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $account->description }}</p>
                            </div>
                            <form action="{{ route('admin.users.deposit-accounts.remove', [$user, $account]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/50 dark:text-red-400 dark:hover:bg-red-900 transition-colors">
                                    <i class="material-symbols-outlined text-base mr-1">remove_circle</i>
                                    Remove
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-700 mb-4">
                        <i class="material-symbols-outlined text-3xl text-slate-400">account_balance_off</i>
                    </div>
                    <p class="text-slate-500 dark:text-slate-400">No deposit accounts assigned yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Assign New Deposit Account -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
        <div class="px-8 py-6 border-b border-slate-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center">
                <i class="material-symbols-outlined mr-2">add_circle</i>
                Assign New Deposit Account
            </h3>
        </div>
        <div class="p-8">
            <form action="{{ route('admin.users.deposit-accounts.assign', $user) }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label for="deposit_account" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Select Deposit Account
                        </label>
                        <select name="admin_deposit_account_id" id="deposit_account" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-slate-300 dark:border-slate-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-xl dark:bg-slate-700 dark:text-white">
                            <option value="">Select an account</option>
                            @foreach($availableAccounts as $account)
                                <option value="{{ $account->id }}">{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                            <i class="material-symbols-outlined text-lg mr-2">add_circle</i>
                            Assign Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 