@extends('client.layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-100 to-white py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900">Activity History</h1>
                    <p class="mt-2 text-sm text-slate-600">Track all your transactions and activities in one place</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" id="refreshBtn" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-solid fa-arrows-rotate mr-2"></i>
                        Refresh
                    </button>
                    <div class="relative">
                        <button type="button" id="filterBtn" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <i class="fa-solid fa-filter mr-2 text-blue-500"></i>
                            Filters
                            <i class="fa-solid fa-chevron-down ml-2 transition-transform duration-200" id="filterArrow"></i>
                        </button>

                        <!-- Filter Dropdown -->
                        <div id="filterDropdown" class="hidden opacity-0 transform scale-95 origin-top-right absolute right-0 mt-3 w-80 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 transition-all duration-200 z-50">
                            <div class="p-5 space-y-6">
                                <!-- Date Range Section -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center">
                                        <i class="fa-regular fa-calendar mr-2 text-blue-500"></i>
                                        Date Range
                                    </h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs text-slate-600 mb-1">Start Date</label>
                                            <input type="date" name="start_date" class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm transition-all duration-200" placeholder="Start Date">
                                        </div>
                                        <div>
                                            <label class="block text-xs text-slate-600 mb-1">End Date</label>
                                            <input type="date" name="end_date" class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm transition-all duration-200" placeholder="End Date">
                                        </div>
                                    </div>
                                </div>

                                <!-- Activity Type Section -->
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center">
                                        <i class="fa-solid fa-tag mr-2 text-purple-500"></i>
                                        Activity Type
                                    </h3>
                                    <select name="type" class="block w-full rounded-lg border-slate-200 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm transition-all duration-200">
                                        <option value="">All Activities</option>
                                        <option value="{{ \App\Models\UserActivity::TYPE_ASSET_TRANSFER }}">Asset Transfers</option>
                                        <option value="{{ \App\Models\UserActivity::TYPE_OTC_TRADE }}">OTC Trades</option>
                                        <option value="{{ \App\Models\UserActivity::TYPE_CRYPTO_WITHDRAWAL }}">Crypto Withdrawals</option>
                                        <option value="{{ \App\Models\UserActivity::TYPE_FIAT_WITHDRAWAL }}">Fiat Withdrawals</option>
                                        <option value="{{ \App\Models\UserActivity::TYPE_DEPOSIT }}">Deposits</option>
                                    </select>
                                </div>

                                <!-- Status Section -->
                                <div class="filter-status">
                                    <h3 class="text-sm font-semibold text-slate-900 mb-3 flex items-center">
                                        <i class="fa-solid fa-circle-check mr-2 text-emerald-500"></i>
                                        Status
                                    </h3>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="relative flex items-center p-3 rounded-lg border border-slate-200 hover:border-blue-500 cursor-pointer transition-all duration-200">
                                            <input type="radio" name="status" value="" class="peer sr-only">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-asterisk mr-2 text-slate-400"></i>
                                                <span class="text-sm text-slate-600">All</span>
                                            </div>
                                            <div class="absolute inset-0 rounded-lg peer-checked:border-2 peer-checked:border-blue-500 pointer-events-none"></div>
                                        </label>
                                        <label class="relative flex items-center p-3 rounded-lg border border-slate-200 hover:border-emerald-500 cursor-pointer transition-all duration-200">
                                            <input type="radio" name="status" value="{{ \App\Models\UserActivity::STATUS_COMPLETED }}" class="peer sr-only">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-check mr-2 text-emerald-500"></i>
                                                <span class="text-sm text-slate-600">Completed</span>
                                            </div>
                                            <div class="absolute inset-0 rounded-lg peer-checked:border-2 peer-checked:border-emerald-500 pointer-events-none"></div>
                                        </label>
                                        <label class="relative flex items-center p-3 rounded-lg border border-slate-200 hover:border-amber-500 cursor-pointer transition-all duration-200">
                                            <input type="radio" name="status" value="{{ \App\Models\UserActivity::STATUS_PENDING }}" class="peer sr-only">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-clock mr-2 text-amber-500"></i>
                                                <span class="text-sm text-slate-600">Pending</span>
                                            </div>
                                            <div class="absolute inset-0 rounded-lg peer-checked:border-2 peer-checked:border-amber-500 pointer-events-none"></div>
                                        </label>
                                        <label class="relative flex items-center p-3 rounded-lg border border-slate-200 hover:border-red-500 cursor-pointer transition-all duration-200">
                                            <input type="radio" name="status" value="{{ \App\Models\UserActivity::STATUS_FAILED }}" class="peer sr-only">
                                            <div class="flex items-center">
                                                <i class="fa-solid fa-xmark mr-2 text-red-500"></i>
                                                <span class="text-sm text-slate-600">Failed</span>
                                            </div>
                                            <div class="absolute inset-0 rounded-lg peer-checked:border-2 peer-checked:border-red-500 pointer-events-none"></div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center gap-3 pt-2">
                                    <button type="button" id="resetFiltersBtn" class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all duration-200">
                                        <i class="fa-solid fa-rotate-left mr-2"></i>
                                        Reset
                                    </button>
                                    <button type="button" id="applyFiltersBtn" class="flex-1 inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <i class="fa-solid fa-check mr-2"></i>
                                        Apply Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-check-circle text-emerald-600 text-3xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Completed Activities</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $activities->where('status', 'completed')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-clock text-amber-600 text-3xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Pending Activities</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $activities->where('status', 'pending')->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-arrows-rotate text-blue-600 text-3xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Transactions</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $activities->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-calendar text-purple-600 text-3xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">This Month</dt>
                                    <dd class="text-lg font-semibold text-gray-900">{{ $activities->where('created_at', '>=', now()->startOfMonth())->count() }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden ring-1 ring-slate-200">
            @forelse($activities as $activity)
                <div class="p-6 flex items-start gap-6 {{ !$loop->last ? 'border-b border-slate-200' : '' }} hover:bg-slate-50/70 transition-all duration-200 group">
                    <!-- Icon with dynamic color based on activity type -->
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-opacity-10 flex items-center justify-center
                            {{ $activity->activity_type === \App\Models\UserActivity::TYPE_ASSET_TRANSFER ? 'bg-green-100 text-green-600' :
                            ($activity->activity_type === \App\Models\UserActivity::TYPE_OTC_TRADE ? 'bg-purple-100 text-purple-600' :
                            ($activity->activity_type === \App\Models\UserActivity::TYPE_CRYPTO_WITHDRAWAL ? 'bg-orange-100 text-orange-600' :
                            ($activity->activity_type === \App\Models\UserActivity::TYPE_FIAT_WITHDRAWAL ? 'bg-red-100 text-red-600' :
                            'bg-emerald-100 text-emerald-600'))) }}
                            group-hover:scale-110 transform transition-transform duration-200">
                            <i class="fa-solid {{ $activity->icon_class }} text-2xl"></i>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-grow">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-slate-900">
                                        {{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}
                                    </h3>
                                    <!-- Dynamic Status Badge -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $activity->status === 'completed' ? 'bg-emerald-100 text-emerald-700' :
                                        ($activity->status === 'pending' ? 'bg-amber-100 text-amber-700' :
                                        ($activity->status === 'failed' ? 'bg-red-100 text-red-700' :
                                        'bg-slate-100 text-slate-700')) }}">
                                        <i class="fa-solid {{ $activity->status === 'completed' ? 'fa-check-circle' :
                                            ($activity->status === 'pending' ? 'fa-clock' :
                                            ($activity->status === 'failed' ? 'fa-times-circle' :
                                            'fa-ban')) }} mr-1.5 text-xs"></i>
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600 mt-1">{{ $activity->description }}</p>
                            </div>
                            <span class="text-sm text-slate-500 flex items-center">
                                <i class="fa-regular fa-clock mr-1.5"></i>
                                {{ $activity->created_at->format('M d, Y H:i A') }}
                            </span>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex items-center gap-6">
                                @if($activity->amount)
                                    <div class="flex items-center text-slate-700">
                                        <i class="fa-solid fa-coins mr-2 text-amber-500"></i>
                                        <span class="font-medium">
                                            {{ number_format($activity->amount, 8) }}
                                        </span>
                                        <span class="text-slate-500 ml-1">{{ $activity->currency_symbol }}</span>
                                    </div>
                                @endif

                                <div class="flex items-center text-slate-600">
                                    <i class="fa-solid fa-hashtag mr-2 text-blue-500"></i>
                                    <span>{{ $activity->reference_number }}</span>
                                </div>

                                @if($activity->metadata)
                                    <div class="flex items-center text-slate-600">
                                        @if(isset($activity->metadata['fee']))
                                            <i class="fa-solid fa-receipt mr-2 text-purple-500"></i>
                                            <span>Fee: {{ number_format($activity->metadata['fee'], 8) }} {{ $activity->currency_symbol }}</span>

                                        @elseif(isset($activity->metadata['network_fee']) && $activity->metadata['network_fee'])
                                            <i class="fa-solid fa-receipt mr-2 text-purple-500"></i>
                                            <span>Network Fee: {{ number_format($activity->metadata['network_fee'], 8) }} {{ $activity->currency_symbol }}</span>
                                        @endif

                                        
                                    </div>
                                    <div class="flex items-center text-slate-600">
                                    @if(isset($activity->metadata['exchange_rate']))
                                            <i class="fa-solid fa-exchange-alt mr-2 text-green-500"></i>
                                            <span>Rate: {{ number_format($activity->metadata['exchange_rate'], 8) }}</span>
                                        @endif
                                        </div>

                                @endif
                            </div>

                            <a href="{{ $activity->route }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-700
                                bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                                <span>View Details</span>
                                <i class="fa-solid fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 mb-4">
                        <i class="fa-solid fa-inbox text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">No Activities Found</h3>
                    <p class="text-slate-500">There are no activities matching your current filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="mt-6">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('post-script')
<script>
$(document).ready(function() {
    // Handle filter dropdown
    $('#filterBtn').on('click', function(e) {
        e.stopPropagation();
        const dropdown = $('#filterDropdown');
        const arrow = $('#filterArrow');

        if (dropdown.hasClass('hidden')) {
            dropdown.removeClass('hidden');
            setTimeout(() => {
                dropdown.removeClass('opacity-0 scale-95').addClass('opacity-100 scale-100');
                arrow.addClass('rotate-180');
            }, 10);
        } else {
            dropdown.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95');
            arrow.removeClass('rotate-180');
            setTimeout(() => {
                dropdown.addClass('hidden');
            }, 200);
        }
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#filterDropdown').length && !$(e.target).closest('#filterBtn').length) {
            const dropdown = $('#filterDropdown');
            const arrow = $('#filterArrow');

            dropdown.removeClass('opacity-100 scale-100').addClass('opacity-0 scale-95');
            arrow.removeClass('rotate-180');
            setTimeout(() => {
                dropdown.addClass('hidden');
            }, 200);
        }
    });

    // Handle refresh button
    $('#refreshBtn').on('click', function() {
        window.location.reload();
    });

    // Handle reset filters
    $('#resetFiltersBtn').on('click', function() {
        $('input[name="start_date"]').val('');
        $('input[name="end_date"]').val('');
        $('select[name="type"]').val('');
        $('input[name="status"]').prop('checked', false);
        $('input[name="status"][value=""]').prop('checked', true);
    });

    // Handle apply filters
    $('#applyFiltersBtn').on('click', function() {
        const type = $('select[name="type"]').val();
        const status = $('input[name="status"]:checked').val();
        const startDate = $('input[name="start_date"]').val();
        const endDate = $('input[name="end_date"]').val();

        let url = new URL(window.location.href);

        if (type) url.searchParams.set('type', type);
        else url.searchParams.delete('type');

        if (status) url.searchParams.set('status', status);
        else url.searchParams.delete('status');

        if (startDate) url.searchParams.set('start_date', startDate);
        else url.searchParams.delete('start_date');

        if (endDate) url.searchParams.set('end_date', endDate);
        else url.searchParams.delete('end_date');

        window.location.href = url.toString();
    });

    // Set initial values from URL params
    const urlParams = new URLSearchParams(window.location.search);
    $('select[name="type"]').val(urlParams.get('type') || '');
    $(`input[name="status"][value="${urlParams.get('status') || ''}"]`).prop('checked', true);
    $('input[name="start_date"]').val(urlParams.get('start_date') || '');
    $('input[name="end_date"]').val(urlParams.get('end_date') || '');
});
</script>
@endsection
