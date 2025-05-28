@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">User Activities</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">View and manage all user activities in the system</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Activities</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activities->total() }}</div>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800">
                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400 mb-1">Completed</div>
                <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-500">
                    {{ $activities->where('status', 'completed')->count() }}
                </div>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
                <div class="text-sm font-medium text-amber-600 dark:text-amber-400 mb-1">Pending</div>
                <div class="text-2xl font-bold text-amber-700 dark:text-amber-500">
                    {{ $activities->where('status', 'pending')->count() }}
                </div>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                <div class="text-sm font-medium text-red-600 dark:text-red-400 mb-1">Failed</div>
                <div class="text-2xl font-bold text-red-700 dark:text-red-500">
                    {{ $activities->where('status', 'failed')->count() }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
        <!-- Table Filters -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
            <form method="GET" action="{{ route('admin.activities.index') }}" class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <select name="activity_type" class="form-select pl-3 pr-10 py-2 text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-700 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Types</option>
                            <option value="asset_transfer" {{ request('activity_type') === 'asset_transfer' ? 'selected' : '' }}>Asset Transfer</option>
                            <option value="otc_trade" {{ request('activity_type') === 'otc_trade' ? 'selected' : '' }}>OTC Trade</option>
                            <option value="crypto_withdrawal" {{ request('activity_type') === 'crypto_withdrawal' ? 'selected' : '' }}>Crypto Withdrawal</option>
                            <option value="fiat_withdrawal" {{ request('activity_type') === 'fiat_withdrawal' ? 'selected' : '' }}>Fiat Withdrawal</option>
                            <option value="deposit" {{ request('activity_type') === 'deposit' ? 'selected' : '' }}>Deposit</option>
                        </select>
                    </div>
                    <div class="relative">
                        <select name="status" class="form-select pl-3 pr-10 py-2 text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-700 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg">
                        <i class="material-symbols-outlined text-lg mr-1">filter_list</i>
                        Filter
                    </button>
                </div>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activities..."
                           class="form-input pl-10 pr-4 py-2 text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-700 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">

                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                <div class="grid grid-cols-12 gap-4 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                    <div class="col-span-3">Type & Date</div>
                    <div class="col-span-3">Counterparty</div>
                    <div class="col-span-2">Reference No.</div>
                    <div class="col-span-2">Status</div>
                    <div class="col-span-2 text-right">Amount</div>
                </div>
            </div>

            <!-- Activities List -->
            <div class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($activities as $activity)
                    <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- Type & Date -->
                            <div class="col-span-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg
                                        {{ $activity->activity_type === 'asset_transfer' ? 'bg-blue-100 dark:bg-blue-900/50' :
                                           ($activity->activity_type === 'otc_trade' ? 'bg-purple-100 dark:bg-purple-900/50' :
                                           'bg-slate-100 dark:bg-slate-700') }}">
                                        <i class="fa-solid {{ $activity->icon_class }} text-lg
                                            {{ $activity->activity_type === 'asset_transfer' ? 'text-blue-600 dark:text-blue-400' :
                                               ($activity->activity_type === 'otc_trade' ? 'text-purple-600 dark:text-purple-400' :
                                               'text-slate-500 dark:text-slate-400') }}"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ ucwords(str_replace('_', ' ', $activity->activity_type)) }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $activity->created_at->format('M d, Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Counterparty -->
                            <div class="col-span-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($activity->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $activity->user->name }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $activity->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reference No. -->
                            <div class="col-span-2">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ $activity->reference_number }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ ucfirst($activity->action) }}
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-span-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $activity->status_color_class }}
                                    {{ $activity->status === 'completed' ? 'bg-emerald-100 dark:bg-emerald-900/50' :
                                       ($activity->status === 'pending' ? 'bg-amber-100 dark:bg-amber-900/50' :
                                       ($activity->status === 'failed' ? 'bg-red-100 dark:bg-red-900/50' :
                                       'bg-slate-100 dark:bg-slate-900/50')) }}">
                                    <i class="material-symbols-outlined text-[18px] mr-1">
                                        {{ $activity->status === 'completed' ? 'check_circle' :
                                           ($activity->status === 'failed' ? 'error' :
                                           ($activity->status === 'cancelled' ? 'cancel' : 'pending')) }}
                                    </i>
                                    {{ ucfirst($activity->status) }}
                                </span>
                            </div>

                            <!-- Amount -->
                            <div class="col-span-2 text-right">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ number_format($activity->amount, 8) }} {{ $activity->currency_symbol }}
                                </div>
                                @if(isset($activity->metadata['fee']) && $activity->metadata['fee'] > 0)
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        Fee: {{ number_format($activity->metadata['fee'], 8) }} {{ $activity->currency_symbol }}
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <a href="{{ route('admin.activities.show', $activity) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <i class="material-symbols-outlined text-lg mr-1">visibility</i>
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <div class="flex flex-col items-center">
                            <div class="h-12 w-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-4">
                                <i class="material-symbols-outlined text-2xl text-slate-400">history</i>
                            </div>
                            <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-1">No Activities Found</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">No user activities are available at the moment.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                {{ $activities->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
