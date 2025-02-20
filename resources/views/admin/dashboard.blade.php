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

        <!-- Recent Activities Table -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-slate-500/30 to-slate-600/30 rounded-2xl blur opacity-30"></div>
            <div class="relative bg-white dark:bg-slate-800 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Recent Activities</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach($recentActivities as $activity)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                    {{ $activity->user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                    {{ $activity->reference_number }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($activity->status === 'completed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-400
                                        @elseif($activity->status === 'pending') bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-400
                                        @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400 @endif">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                    {{ $activity->created_at->format('M d, Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
