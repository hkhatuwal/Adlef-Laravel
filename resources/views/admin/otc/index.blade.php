@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">OTC Trades</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">View and manage all over-the-counter trades in the system</p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
                <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">Total Trades</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $otcRequests->total() }}</div>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-4 border border-emerald-200 dark:border-emerald-800">
                <div class="text-sm font-medium text-emerald-600 dark:text-emerald-400 mb-1">Completed</div>
                <div class="text-2xl font-bold text-emerald-700 dark:text-emerald-500">
                    {{ $otcRequests->where('status', 'completed')->count() }}
                </div>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-4 border border-amber-200 dark:border-amber-800">
                <div class="text-sm font-medium text-amber-600 dark:text-amber-400 mb-1">Pending</div>
                <div class="text-2xl font-bold text-amber-700 dark:text-amber-500">
                    {{ $otcRequests->where('status', 'pending')->count() }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
        <!-- Table Filters -->
        <div class="p-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <select class="form-select pl-3 pr-10 py-2 text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-700 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="completed">Completed</option>
                            <option value="processing">Processing</option>
                            <option value="failed">Failed</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>
                <div class="relative">
                    <input type="text" placeholder="Search trades..." 
                           class="form-input pl-10 pr-4 py-2 text-sm border-slate-300 dark:border-slate-600 dark:bg-slate-700 rounded-lg focus:border-indigo-500 focus:ring-indigo-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="material-symbols-outlined text-slate-400">search</i>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-700/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">From</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">To</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Exchange Rate</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Network Fee</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($otcRequests as $otc)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                        <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                            {{ strtoupper(substr($otc->user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $otc->user->name }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $otc->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ number_format($otc->from_amount, 8) }} {{ $otc->fromCurrency->symbol }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ number_format($otc->to_amount, 8) }} {{ $otc->toCurrency->symbol }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ number_format($otc->exchange_rate, 4) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ number_format($otc->network_fee, 8) }} {{ $otc->fromCurrency->symbol }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                    {{ $otc->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' : 
                                       ($otc->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' : 
                                       'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400') }}">
                                    <i class="material-symbols-outlined text-[18px] mr-1">
                                        {{ $otc->status === 'completed' ? 'check_circle' : 
                                           ($otc->status === 'failed' ? 'error' : 'pending') }}
                                    </i>
                                    {{ ucfirst($otc->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.otc.show', $otc) }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <i class="material-symbols-outlined text-lg mr-1">visibility</i>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-12 w-12 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-4">
                                        <i class="material-symbols-outlined text-2xl text-slate-400">currency_exchange</i>
                                    </div>
                                    <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-1">No OTC Trades Found</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">No over-the-counter trades are available at the moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($otcRequests->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50">
                {{ $otcRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 