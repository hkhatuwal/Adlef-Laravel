@extends('admin._partials.admin_main')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard Overview</h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Monitor your platform's performance and key metrics</p>
        </div>

        <!-- Financial Metrics -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-3">
            <!-- Revenue Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-green-50 dark:bg-green-900/50">
                            <i class="fas fa-money-bill-wave text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Total Revenue</dt>
                                <dd class="mt-1">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalRevenue, 2) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cost Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-red-500 to-rose-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-red-50 dark:bg-red-900/50">
                            <i class="fas fa-file-invoice-dollar text-2xl text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Total Costs</dt>
                                <dd class="mt-1">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalCosts, 2) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profit Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-blue-50 dark:bg-blue-900/50">
                            <i class="fas fa-chart-line text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Total Profit</dt>
                                <dd class="mt-1">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalProfit, 2) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Users Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-indigo-50 dark:bg-indigo-900/50">
                            <i class="fas fa-users text-2xl text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Total Users</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                        <i class="fas fa-arrow-up mr-1 text-xs"></i>
                                        <span>+{{ $newUsersToday }} today</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Activities Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-purple-50 dark:bg-purple-900/50">
                            <i class="fas fa-chart-line text-2xl text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Total Activities</dt>
                                <dd class="mt-1">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($totalActivities) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verification Rate Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/50">
                            <i class="fas fa-check-circle text-2xl text-emerald-600 dark:text-emerald-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Verification Rate</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($verificationRate, 1) }}%</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-slate-600 dark:text-slate-400">
                                        <span>{{ $verifiedUsers }} verified</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Activities Card -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-amber-50 dark:bg-amber-900/50">
                            <i class="fas fa-clock text-2xl text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">Pending Activities</dt>
                                <dd class="mt-1">
                                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($pendingActivities) }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency Last Updated Section -->
        @if($firstCurrency && $currencyLastUpdated)
        <div class="mb-8">
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-cyan-500 to-sky-500 rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-200"></div>
                <div class="relative p-6 bg-white dark:bg-slate-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 rounded-lg bg-cyan-50 dark:bg-cyan-900/50">
                            <i class="fas fa-sync-alt text-2xl text-cyan-600 dark:text-cyan-400"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-slate-500 dark:text-slate-400 truncate">
                                    Currency  Last Updated
                                </dt>
                                <dd class="mt-1 flex items-baseline">
                                    <div class="text-xl font-bold text-slate-900 dark:text-white">
                                        {{ $currencyLastUpdated->diffForHumans() }}
                                    </div>
                                    <div class="ml-2 flex items-baseline text-sm font-medium text-slate-600 dark:text-slate-400">
                                        <span>{{ $currencyLastUpdated->format('M d, Y H:i') }}</span>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 mb-8">
            <!-- User Registration Chart -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500/30 to-indigo-500/30 rounded-2xl blur opacity-30"></div>
                <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">User Registrations</h3>
                    <div class="h-[300px]">
                        <canvas id="userRegistrationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Activity Types Chart -->
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-purple-500/30 to-pink-500/30 rounded-2xl blur opacity-30"></div>
                <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Activities by Type</h3>
                    <div class="h-[300px]">
                        <canvas id="activityTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Section -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-500/30 to-slate-600/30 rounded-2xl blur opacity-30"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden border border-slate-200 dark:border-slate-700">
                <!-- Section Header with Stats -->
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Activities</h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Latest user activities in the system</p>
                        </div>
                        <a href="{{ route('admin.activities.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                            <i class="material-symbols-outlined text-lg mr-1">arrow_forward</i>
                            View All
                        </a>
                    </div>

                    <!-- Activity Stats Cards -->
                    <div class="grid grid-cols-4 gap-4">
                        <div class="bg-white dark:bg-slate-800 rounded-xl p-3 border border-slate-200 dark:border-slate-700">
                            <div class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total</div>
                            <div class="text-lg font-bold text-slate-900 dark:text-white">{{ $recentActivities->count() }}</div>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-3 border border-emerald-200 dark:border-emerald-800">
                            <div class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-1">Completed</div>
                            <div class="text-lg font-bold text-emerald-700 dark:text-emerald-500">
                                {{ $recentActivities->where('status', 'completed')->count() }}
                            </div>
                        </div>
                        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-3 border border-amber-200 dark:border-amber-800">
                            <div class="text-xs font-medium text-amber-600 dark:text-amber-400 mb-1">Pending</div>
                            <div class="text-lg font-bold text-amber-700 dark:text-amber-500">
                                {{ $recentActivities->where('status', 'pending')->count() }}
                            </div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-3 border border-red-200 dark:border-red-800">
                            <div class="text-xs font-medium text-red-600 dark:text-red-400 mb-1">Failed</div>
                            <div class="text-lg font-bold text-red-700 dark:text-red-500">
                                {{ $recentActivities->where('status', 'failed')->count() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activities List -->
                <div class="overflow-x-auto">
                    <!-- Header -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                        <div class="grid grid-cols-12 gap-4 text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wider">
                            <div class="col-span-3">Type & Date</div>
                            <div class="col-span-3">Counterparty</div>
                            <div class="col-span-2">Reference No.</div>
                            <div class="col-span-2">Status</div>
                            <div class="col-span-2 text-right">Amount</div>
                        </div>
                    </div>

                    <!-- Activities List -->
                    <div class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($recentActivities as $activity)
                            <div class="px-6 py-4 hover:bg-slate-50 dark:hover:bg-slate-700/25 transition-colors">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <!-- Type & Date -->
                                    <div class="col-span-3">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg
                                                {{ $activity->activity_type === 'asset_transfer' ? 'bg-blue-100 dark:bg-blue-900/50' :
                                                   ($activity->activity_type === 'otc_trade' ? 'bg-purple-100 dark:bg-purple-900/50' :
                                                   'bg-slate-100 dark:bg-slate-700') }}">
                                                <i class="fa-solid {{ $activity->icon_class ?? 'fa-exchange-alt' }} text-lg
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
                                                    {{ $activity->description ?? 'Activity' }}
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
                                            {{ ucfirst($activity->action ?? 'Activity') }}
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-span-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            {{ $activity->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400' :
                                               ($activity->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400' :
                                               ($activity->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400' :
                                               'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-400')) }}">
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
                                            {{ number_format($activity->amount ?? 0, 8) }} {{ $activity->currency_symbol ?? 'USD' }}
                                        </div>
                                        @if(isset($activity->metadata['fee']) && $activity->metadata['fee'] > 0)
                                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                                Fee: {{ number_format($activity->metadata['fee'], 8) }} {{ $activity->currency_symbol ?? 'USD' }}
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
                                    <p class="text-sm text-slate-500 dark:text-slate-400">No recent activities are available at the moment.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('post-script')
<script src="{{asset('common/js/chart.js')}}"></script>
<script>
    // User Registration Chart
    const userRegistrationCtx = document.getElementById('userRegistrationChart').getContext('2d');
    new Chart(userRegistrationCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($userRegistrationData->pluck('date')) !!},
            datasets: [{
                label: 'New Users',
                data: {!! json_encode($userRegistrationData->pluck('count')) !!},
                borderColor: '#4F46E5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#4F46E5'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Activity Types Chart
    const activityTypesCtx = document.getElementById('activityTypesChart').getContext('2d');
    new Chart(activityTypesCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($activitiesByType->pluck('activity_type')->map(function($type) {
                return ucfirst(str_replace('_', ' ', $type));
            })) !!},
            datasets: [{
                data: {!! json_encode($activitiesByType->pluck('count')) !!},
                backgroundColor: [
                    '#4F46E5', // Indigo
                    '#10B981', // Emerald
                    '#F59E0B', // Amber
                    '#EF4444', // Red
                    '#6366F1', // Blue
                    '#8B5CF6', // Purple
                    '#EC4899'  // Pink
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            },
            cutout: '70%'
        }
    });
</script>
@endsection
