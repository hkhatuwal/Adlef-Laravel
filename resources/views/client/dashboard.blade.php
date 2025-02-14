@extends('client.layouts.app')

@section('page-title', 'Asset Holdings')

@section('content')
<div class="space-y-8 py-4">
    <!-- Portfolio Overview Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Portfolio Value Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 mr-3">
                        <i class="material-symbols-outlined text-xl">account_balance_wallet</i>
                    </span>
                    <h3 class="text-sm font-medium text-slate-900">Portfolio Value</h3>
                </div>
                <span class="text-xs text-slate-500">Last updated {{ $portfolioStats['last_updated']->diffForHumans() }}</span>
            </div>
            <div class="flex items-baseline">
                <span class="text-2xl font-bold text-slate-900">${{ number_format($portfolioStats['total_value'], 2) }}</span>
                <span class="ml-2 text-sm text-slate-500">USD</span>
            </div>
            <div class="mt-2 flex items-center">
                <span class="inline-flex items-center {{ $portfolioStats['total_change_24h'] >= 0 ? 'text-emerald-600' : 'text-red-600' }} text-sm">
                    <i class="material-symbols-outlined text-base mr-1">{{ $portfolioStats['total_change_24h'] >= 0 ? 'trending_up' : 'trending_down' }}</i>
                    {{ abs($portfolioStats['total_change_24h_percentage']) }}%
                </span>
                <span class="text-xs text-slate-500 ml-2">24h change</span>
            </div>
        </div>

        <!-- Asset Distribution Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center mb-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-purple-50 text-purple-600 mr-3">
                    <i class="material-symbols-outlined text-xl">pie_chart</i>
                </span>
                <h3 class="text-sm font-medium text-slate-900">Asset Distribution</h3>
            </div>
            <div class="space-y-3">
                @foreach($assetClasses as $class)
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <div class="flex items-center">
                            <i class="material-symbols-outlined text-base mr-2">{{ $class['icon'] }}</i>
                            <span class="font-medium text-slate-700">{{ $class['name'] }}</span>
                        </div>
                        <span class="text-slate-500">{{ $class['percentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <span class="bg-blue-500 bg-purple-500"></span>
                        <div class=" {{ $class['color'] }} h-1.5 rounded-full" style="width: {{ $class['percentage'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center mb-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 mr-3">
                    <i class="material-symbols-outlined text-xl">analytics</i>
                </span>
                <h3 class="text-sm font-medium text-slate-900">Portfolio Stats</h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-slate-500">Asset Classes</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $portfolioStats['asset_classes'] }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Assets</p>
                    <p class="text-lg font-semibold text-slate-900">{{ $portfolioStats['total_assets'] }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center mb-4">
                <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50 text-blue-600 mr-3">
                    <i class="material-symbols-outlined text-xl">bolt</i>
                </span>
                <h3 class="text-sm font-medium text-slate-900">Quick Actions</h3>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{route('client.transfer')}}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                    <i class="material-symbols-outlined text-base mr-2">add_circle</i>
                    <span class="text-sm font-medium">Asset Transfer </span>
                </a>
                <a href="{{route('client.otc.index')}}" class="inline-flex items-center justify-center px-4 py-2 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="material-symbols-outlined text-base mr-2">swap_horiz</i>
                    <span class="text-sm font-medium">OTC</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Assets Table Section -->
    <div>
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-900">Your Assets</h2>
            <p class="mt-1 text-sm text-slate-600">View and manage your asset holdings</p>
        </div>

        <x-table.data-table
            :headers="$assetHeaders"
            :rows="$assetRows"
            table-id="assets-table"
            empty-message="No assets found"
            empty-icon="account_balance_wallet"
            empty-description="Your portfolio is empty. Start by adding some assets."
        />
    </div>

    <!-- Recent Activities Section -->
    <div>
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-900">Recent Activities</h2>
            <p class="mt-1 text-sm text-slate-600">Track your recent transactions and activities</p>
        </div>

        <x-table.data-table
            :headers="$activityHeaders"
            :rows="$activityRows"
            table-id="activities-table"
            empty-message="No recent activities"
            empty-icon="history"
            empty-description="Your activity history is empty."
        />
    </div>
</div>
@endsection


