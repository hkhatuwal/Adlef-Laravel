@extends('admin._partials.admin_main')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Payment Transactions</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Monitor and manage all payment transactions</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.payment-transactions.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                <i class="material-symbols-outlined mr-2 text-[18px]">download</i>
                Export CSV
            </a>
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

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <i class="material-symbols-outlined text-red-500 mr-2">error</i>
                <p class="text-red-600 dark:text-red-400">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Dashboard Statistics -->
    <div class="bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-6 mb-8">
        <!-- Dashboard Header with Toggle -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Transaction Dashboard</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Overview of payment transaction metrics</p>
            </div>
            <button id="dashboardToggle" 
                    class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200"
                    onclick="toggleDashboard()">
                <i id="dashboardToggleIcon" class="material-symbols-outlined text-sm mr-2 transition-transform duration-200">expand_less</i>
                <span id="dashboardToggleText">Hide Dashboard</span>
            </button>
        </div>
        
        <!-- Dashboard Content -->
        <div id="dashboardContent" class="transition-all duration-300 ease-in-out">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Transactions -->
        <div class="group bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/50 dark:border-slate-700/50 hover:border-indigo-300 dark:hover:border-indigo-600">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="material-symbols-outlined text-white text-xl">receipt_long</i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Transactions</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($totalTransactions) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-slate-500 dark:text-slate-400">
                        <i class="material-symbols-outlined text-xs mr-1">trending_up</i>
                        <span>All time</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Amount -->
        <div class="group bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/50 dark:border-slate-700/50 hover:border-emerald-300 dark:hover:border-emerald-600">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="material-symbols-outlined text-white text-xl">attach_money</i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Total Amount</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">${{ number_format($totalAmountUSD, 2) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-emerald-600 dark:text-emerald-400">
                        <i class="material-symbols-outlined text-xs mr-1">currency_exchange</i>
                        <span>Converted to USD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Successful Transactions -->
        <div class="group bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/50 dark:border-slate-700/50 hover:border-emerald-300 dark:hover:border-emerald-600">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="material-symbols-outlined text-white text-xl">check_circle</i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Successful</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($successfulTransactions) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-emerald-600 dark:text-emerald-400">
                            <i class="material-symbols-outlined text-xs mr-1">monetization_on</i>
                            <span>${{ number_format($successfulAmountUSD, 2) }}</span>
                        </div>
                        @if($totalTransactions > 0)
                            <span class="text-slate-500 dark:text-slate-400">
                                {{ number_format(($successfulTransactions / $totalTransactions) * 100, 1) }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed Transactions -->
        <div class="group bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/50 dark:border-slate-700/50 hover:border-red-300 dark:hover:border-red-600">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="material-symbols-outlined text-white text-xl">error</i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Failed</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-white">{{ number_format($failedTransactions) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-red-600 dark:text-red-400">
                            <i class="material-symbols-outlined text-xs mr-1">warning</i>
                            <span>Requires attention</span>
                        </div>
                        @if($totalTransactions > 0)
                            <span class="text-slate-500 dark:text-slate-400">
                                {{ number_format(($failedTransactions / $totalTransactions) * 100, 1) }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- Status Breakdown -->
    <div class="bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Transaction Status Overview</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Distribution of transactions by status</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                    <i class="material-symbols-outlined text-base">analytics</i>
                    <span>Real-time data</span>
                </div>
                <button id="statusToggle" 
                        class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200"
                        onclick="toggleStatusBreakdown()">
                    <i id="statusToggleIcon" class="material-symbols-outlined text-sm mr-1 transition-transform duration-200">expand_less</i>
                    <span id="statusToggleText">Hide</span>
                </button>
            </div>
        </div>
        
        <!-- Status Breakdown Content -->
        <div id="statusContent" class="transition-all duration-300 ease-in-out">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $statusConfig = [
                    'completed' => [
                        'label' => 'Completed', 
                        'color' => 'from-emerald-500 to-emerald-600',
                        'bgColor' => 'bg-emerald-50 dark:bg-emerald-900/20',
                        'textColor' => 'text-emerald-700 dark:text-emerald-300',
                        'icon' => 'check_circle'
                    ],
                    'failed' => [
                        'label' => 'Failed', 
                        'color' => 'from-red-500 to-red-600',
                        'bgColor' => 'bg-red-50 dark:bg-red-900/20',
                        'textColor' => 'text-red-700 dark:text-red-300',
                        'icon' => 'error'
                    ],
                    'pending' => [
                        'label' => 'Pending', 
                        'color' => 'from-yellow-500 to-yellow-600',
                        'bgColor' => 'bg-yellow-50 dark:bg-yellow-900/20',
                        'textColor' => 'text-yellow-700 dark:text-yellow-300',
                        'icon' => 'schedule'
                    ],
                    'processing' => [
                        'label' => 'Processing', 
                        'color' => 'from-blue-500 to-blue-600',
                        'bgColor' => 'bg-blue-50 dark:bg-blue-900/20',
                        'textColor' => 'text-blue-700 dark:text-blue-300',
                        'icon' => 'sync'
                    ],
                    'checkout_pending' => [
                        'label' => 'Checkout Pending', 
                        'color' => 'from-amber-500 to-amber-600',
                        'bgColor' => 'bg-amber-50 dark:bg-amber-900/20',
                        'textColor' => 'text-amber-700 dark:text-amber-300',
                        'icon' => 'shopping_cart'
                    ],
                    'cancelled' => [
                        'label' => 'Cancelled', 
                        'color' => 'from-slate-500 to-slate-600',
                        'bgColor' => 'bg-slate-50 dark:bg-slate-900/20',
                        'textColor' => 'text-slate-700 dark:text-slate-300',
                        'icon' => 'cancel'
                    ],
                    'refunded' => [
                        'label' => 'Refunded', 
                        'color' => 'from-purple-500 to-purple-600',
                        'bgColor' => 'bg-purple-50 dark:bg-purple-900/20',
                        'textColor' => 'text-purple-700 dark:text-purple-300',
                        'icon' => 'undo'
                    ],
                ];
            @endphp
            
            @foreach($statusConfig as $status => $config)
                @php
                    $count = $statusBreakdown[$status] ?? 0;
                    $percentage = $totalTransactions > 0 ? ($count / $totalTransactions) * 100 : 0;
                @endphp
                <div class="group relative">
                    <div class="bg-white dark:bg-slate-800 rounded-xl p-6 border border-slate-200/50 dark:border-slate-700/50 hover:shadow-lg transition-all duration-300 hover:border-slate-300 dark:hover:border-slate-600">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br {{ $config['color'] }} rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="material-symbols-outlined text-white text-lg">{{ $config['icon'] }}</i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 dark:text-white text-sm">{{ $config['label'] }}</h4>
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($count) }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Progress</span>
                                <span class="font-medium {{ $config['textColor'] }}">{{ number_format($percentage, 1) }}%</span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $config['color'] }} rounded-full transition-all duration-500 ease-out" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Additional Info -->
                        <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 dark:text-slate-400">Status</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $config['bgColor'] }} {{ $config['textColor'] }}">
                                    {{ $config['label'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Summary Stats -->
        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl mb-3">
                        <i class="material-symbols-outlined text-white">trending_up</i>
                    </div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Success Rate</p>
                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                        @if($totalTransactions > 0)
                            {{ number_format(($successfulTransactions / $totalTransactions) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl mb-3">
                        <i class="material-symbols-outlined text-white">pending</i>
                    </div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Pending Rate</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        @if($totalTransactions > 0)
                            {{ number_format(($pendingTransactions / $totalTransactions) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl mb-3">
                        <i class="material-symbols-outlined text-white">error</i>
                    </div>
                    <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Failure Rate</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                        @if($totalTransactions > 0)
                            {{ number_format(($failedTransactions / $totalTransactions) * 100, 1) }}%
                        @else
                            0%
                        @endif
                    </p>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 p-8 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white">Filter Transactions</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Refine your search with advanced filters</p>
            </div>
            <div class="flex items-center space-x-2 text-sm text-slate-500 dark:text-slate-400">
                <i class="material-symbols-outlined text-base">filter_list</i>
                <span>Advanced filters</span>
            </div>
        </div>
        
        <form method="GET" action="{{ route('admin.payment-transactions.index') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        <i class="material-symbols-outlined text-sm mr-1">search</i>
                        Search
                    </label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                               placeholder="Transaction ID, Email, Name..."
                               class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-all duration-200 hover:border-slate-400 dark:hover:border-slate-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="material-symbols-outlined text-slate-400 text-lg">search</i>
                        </div>
                    </div>
                </div>

                <!-- User Filter -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        <i class="material-symbols-outlined text-sm mr-1">person</i>
                        User
                    </label>
                    <select id="user_id" name="user_id" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-all duration-200 hover:border-slate-400 dark:hover:border-slate-500">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        <i class="material-symbols-outlined text-sm mr-1">flag</i>
                        Status
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white transition-all duration-200 hover:border-slate-400 dark:hover:border-slate-500">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Method Type -->
                <div>
                    <label for="payment_method_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Payment Method</label>
                    <select id="payment_method_type" name="payment_method_type" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                        <option value="">All Types</option>
                        @foreach($paymentMethodTypes as $type)
                            <option value="{{ $type }}" {{ request('payment_method_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Card Brand -->
                <div>
                    <label for="card_brand" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Card Brand</label>
                    <select id="card_brand" name="card_brand" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                        <option value="">All Brands</option>
                        @foreach($cardBrands as $brand)
                            <option value="{{ $brand }}" {{ request('card_brand') == $brand ? 'selected' : '' }}>
                                {{ ucfirst($brand) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Gateway -->
                <div>
                    <label for="gateway_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Gateway</label>
                    <select id="gateway_name" name="gateway_name" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                        <option value="">All Gateways</option>
                        @foreach($gateways as $gateway)
                            <option value="{{ $gateway }}" {{ request('gateway_name') == $gateway ? 'selected' : '' }}>
                                {{ ucfirst($gateway) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Currency</label>
                    <select id="currency" name="currency" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                        <option value="">All Currencies</option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency }}" {{ request('currency') == $currency ? 'selected' : '' }}>
                                {{ strtoupper($currency) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Last Four Digits -->
                <div>
                    <label for="last_four" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Last Four Digits</label>
                    <input type="text" id="last_four" name="last_four" value="{{ request('last_four') }}" 
                           placeholder="1234"
                           maxlength="4"
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                </div>

                <!-- Date From -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                </div>

                <!-- Date To -->
                <div>
                    <label for="date_to" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-slate-700 dark:text-white">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-slate-200 dark:border-slate-700 space-y-4 sm:space-y-0">
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="material-symbols-outlined mr-2 text-lg">search</i>
                    Apply Filters
                </button>
                <a href="{{ route('admin.payment-transactions.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition-all duration-200">
                    <i class="material-symbols-outlined mr-2 text-lg">refresh</i>
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Results Summary -->
    <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 rounded-xl p-4 mb-6 border border-slate-200/50 dark:border-slate-700/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2 text-slate-600 dark:text-slate-400">
                    <i class="material-symbols-outlined text-lg">list_alt</i>
                    <span class="text-sm font-medium">Results</span>
                </div>
                <div class="text-sm text-slate-700 dark:text-slate-300">
                    Showing <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $transactions->firstItem() ?? 0 }}</span> to 
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $transactions->lastItem() ?? 0 }}</span> of 
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $transactions->total() }}</span> transactions
                </div>
            </div>
            <div class="flex items-center space-x-2 text-slate-500 dark:text-slate-400">
                <i class="material-symbols-outlined text-sm">info</i>
                <span class="text-xs">Filtered results</span>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-gradient-to-br from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 rounded-2xl shadow-lg border border-slate-200/50 dark:border-slate-700/50 overflow-hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full table-fixed divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-800/50">
                    <tr>
                        <th scope="col" class="w-1/6 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">receipt_long</i>
                                <span>Transaction</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/6 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">person</i>
                                <span>User</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/8 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">attach_money</i>
                                <span>Amount</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/6 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">credit_card</i>
                                <span>Payment</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/8 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">flag</i>
                                <span>Status</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/8 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">account_balance</i>
                                <span>Gateway</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/8 px-4 py-3 text-left text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="material-symbols-outlined text-sm">schedule</i>
                                <span>Date</span>
                            </div>
                        </th>
                        <th scope="col" class="w-1/12 px-4 py-3 text-center text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                            <div class="flex items-center justify-center space-x-1">
                                <i class="material-symbols-outlined text-sm">settings</i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                            <!-- Transaction ID Column -->
                            <td class="px-4 py-3">
                                <div class="truncate">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white truncate" title="{{ $transaction->transaction_id }}">
                                        {{ Str::limit($transaction->transaction_id, 20) }}
                                    </div>
                                    @if($transaction->client_order_id)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate" title="Order: {{ $transaction->client_order_id }}">
                                            Order: {{ Str::limit($transaction->client_order_id, 15) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- User Column -->
                            <td class="px-4 py-3">
                                <div class="truncate">
                                    <div class="text-sm font-medium text-slate-900 dark:text-white truncate" title="{{ $transaction->apiClient->user->name ?? 'N/A' }}">
                                        {{ Str::limit($transaction->apiClient->user->name ?? 'N/A', 20) }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate" title="{{ $transaction->customer_email }}">
                                        {{ Str::limit($transaction->customer_email, 25) }}
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Amount Column -->
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-slate-900 dark:text-white">
                                    {{ number_format($transaction->amount, 2) }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ strtoupper($transaction->currency) }}
                                </div>
                                @if($transaction->gateway_fee || $transaction->our_fee)
                                    <div class="text-xs text-amber-600 dark:text-amber-400">
                                        Fee: {{ number_format(($transaction->gateway_fee ?? 0) + ($transaction->our_fee ?? 0), 2) }}
                                    </div>
                                @endif
                            </td>
                            
                            <!-- Payment Method Column -->
                            <td class="px-4 py-3">
                                @if($transaction->paymentMethod)
                                    <div class="text-sm text-slate-900 dark:text-white truncate" title="{{ $transaction->paymentMethod->getPaymentMethodDisplayName() }}">
                                        {{ Str::limit($transaction->paymentMethod->getPaymentMethodDisplayName(), 18) }}
                                    </div>
                                    @if($transaction->paymentMethod->card_last_four)
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            ****{{ $transaction->paymentMethod->card_last_four }}
                                        </div>
                                    @endif
                                @else
                                    <span class="text-sm text-slate-500 dark:text-slate-400">N/A</span>
                                @endif
                            </td>
                            
                            <!-- Status Column -->
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'checkout_pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400',
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400',
                                        'cancelled' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-400',
                                        'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-slate-100 text-slate-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                            </td>
                            
                            <!-- Gateway Column -->
                            <td class="px-4 py-3">
                                <div class="text-sm text-slate-600 dark:text-slate-400 truncate" title="{{ ucfirst($transaction->gateway_name) }}">
                                    {{ Str::limit(ucfirst($transaction->gateway_name), 12) }}
                                </div>
                            </td>
                            
                            <!-- Date Column -->
                            <td class="px-4 py-3">
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    {{ $transaction->created_at->format('M d') }}
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-500">
                                    {{ $transaction->created_at->format('H:i') }}
                                </div>
                            </td>
                            
                            <!-- Actions Column -->
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.payment-transactions.show', $transaction) }}" 
                                   class="inline-flex items-center px-2 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded-md hover:bg-indigo-100 dark:hover:bg-indigo-900/30 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200"
                                   title="View Details">
                                    <i class="material-symbols-outlined text-sm">visibility</i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center space-y-4">
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                                        <i class="material-symbols-outlined text-2xl text-slate-400 dark:text-slate-500">payment</i>
                                    </div>
                                    <div class="text-center">
                                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No transactions found</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Try adjusting your filters or check back later.</p>
                                        <a href="{{ route('admin.payment-transactions.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                            <i class="material-symbols-outlined mr-2 text-sm">refresh</i>
                                            Clear Filters
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden">
            @forelse($transactions as $transaction)
                <div class="p-4 border-b border-slate-200 dark:border-slate-700 last:border-b-0">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2 mb-1">
                                <h3 class="text-sm font-semibold text-slate-900 dark:text-white truncate">
                                    {{ Str::limit($transaction->transaction_id, 25) }}
                                </h3>
                                @php
                                    $statusColors = [
                                        'checkout_pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400',
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-400',
                                        'completed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400',
                                        'cancelled' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-400',
                                        'refunded' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$transaction->status] ?? 'bg-slate-100 text-slate-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $transaction->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-slate-600 dark:text-slate-400">
                                {{ $transaction->apiClient->user->name ?? 'N/A' }}
                            </p>
                        </div>
                        <a href="{{ route('admin.payment-transactions.show', $transaction) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded-lg hover:bg-indigo-100 dark:hover:bg-indigo-900/30 transition-colors">
                            <i class="material-symbols-outlined text-sm mr-1">visibility</i>
                            View
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Amount:</span>
                            <div class="font-semibold text-slate-900 dark:text-white">
                                {{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}
                            </div>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Gateway:</span>
                            <div class="font-medium text-slate-700 dark:text-slate-300">
                                {{ ucfirst($transaction->gateway_name) }}
                            </div>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Payment Method:</span>
                            <div class="font-medium text-slate-700 dark:text-slate-300">
                                @if($transaction->paymentMethod)
                                    {{ Str::limit($transaction->paymentMethod->getPaymentMethodDisplayName(), 20) }}
                                    @if($transaction->paymentMethod->card_last_four)
                                        <span class="text-slate-500">****{{ $transaction->paymentMethod->card_last_four }}</span>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-slate-500 dark:text-slate-400">Date:</span>
                            <div class="font-medium text-slate-700 dark:text-slate-300">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
                            <i class="material-symbols-outlined text-2xl text-slate-400 dark:text-slate-500">payment</i>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No transactions found</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Try adjusting your filters or check back later.</p>
                            <a href="{{ route('admin.payment-transactions.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                                <i class="material-symbols-outlined mr-2 text-sm">refresh</i>
                                Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

<script>
// Dashboard Toggle Functionality
function toggleDashboard() {
    const content = document.getElementById('dashboardContent');
    const toggleIcon = document.getElementById('dashboardToggleIcon');
    const toggleText = document.getElementById('dashboardToggleText');
    
    if (content.style.display === 'none' || content.style.display === '') {
        // Show dashboard
        content.style.display = 'block';
        content.style.maxHeight = content.scrollHeight + 'px';
        toggleIcon.textContent = 'expand_less';
        toggleText.textContent = 'Hide Dashboard';
        localStorage.setItem('dashboardCollapsed', 'false');
    } else {
        // Hide dashboard
        content.style.maxHeight = '0px';
        setTimeout(() => {
            content.style.display = 'none';
        }, 300);
        toggleIcon.textContent = 'expand_more';
        toggleText.textContent = 'Show Dashboard';
        localStorage.setItem('dashboardCollapsed', 'true');
    }
}

// Status Breakdown Toggle Functionality
function toggleStatusBreakdown() {
    const content = document.getElementById('statusContent');
    const toggleIcon = document.getElementById('statusToggleIcon');
    const toggleText = document.getElementById('statusToggleText');
    
    if (content.style.display === 'none' || content.style.display === '') {
        // Show status breakdown
        content.style.display = 'block';
        content.style.maxHeight = content.scrollHeight + 'px';
        toggleIcon.textContent = 'expand_less';
        toggleText.textContent = 'Hide';
        localStorage.setItem('statusCollapsed', 'false');
    } else {
        // Hide status breakdown
        content.style.maxHeight = '0px';
        setTimeout(() => {
            content.style.display = 'none';
        }, 300);
        toggleIcon.textContent = 'expand_more';
        toggleText.textContent = 'Show';
        localStorage.setItem('statusCollapsed', 'true');
    }
}

// Initialize dashboard and status breakdown state on page load
document.addEventListener('DOMContentLoaded', function() {
    // Dashboard initialization
    const isDashboardCollapsed = localStorage.getItem('dashboardCollapsed') === 'true';
    const dashboardContent = document.getElementById('dashboardContent');
    const dashboardToggleIcon = document.getElementById('dashboardToggleIcon');
    const dashboardToggleText = document.getElementById('dashboardToggleText');
    
    if (isDashboardCollapsed) {
        dashboardContent.style.display = 'none';
        dashboardContent.style.maxHeight = '0px';
        dashboardToggleIcon.textContent = 'expand_more';
        dashboardToggleText.textContent = 'Show Dashboard';
    } else {
        dashboardContent.style.display = 'block';
        dashboardContent.style.maxHeight = 'auto';
        dashboardToggleIcon.textContent = 'expand_less';
        dashboardToggleText.textContent = 'Hide Dashboard';
    }
    
    // Status breakdown initialization
    const isStatusCollapsed = localStorage.getItem('statusCollapsed') === 'true';
    const statusContent = document.getElementById('statusContent');
    const statusToggleIcon = document.getElementById('statusToggleIcon');
    const statusToggleText = document.getElementById('statusToggleText');
    
    if (isStatusCollapsed) {
        statusContent.style.display = 'none';
        statusContent.style.maxHeight = '0px';
        statusToggleIcon.textContent = 'expand_more';
        statusToggleText.textContent = 'Show';
    } else {
        statusContent.style.display = 'block';
        statusContent.style.maxHeight = 'auto';
        statusToggleIcon.textContent = 'expand_less';
        statusToggleText.textContent = 'Hide';
    }
});
</script>
@endsection
