@props(['activity', 'loop'])

<!-- Default Activity Row -->
<div class="px-6 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }} hover:bg-slate-50/50 transition-all duration-150">
    <div class="grid grid-cols-12 gap-4 items-center">
        <!-- Type & Date -->
        <div class="col-span-3">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-opacity-10 flex items-center justify-center
                        {{ $activity->activity_type === \App\Models\UserActivity::TYPE_CRYPTO_WITHDRAWAL ? 'bg-orange-100 text-orange-600' :
                        ($activity->activity_type === \App\Models\UserActivity::TYPE_FIAT_WITHDRAWAL ? 'bg-red-100 text-red-600' :
                        'bg-emerald-100 text-emerald-600') }}">
                        <i class="fa-solid {{ $activity->metadata['icon_class'] ?? 'fa-circle-info' }} text-sm"></i>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900">
                        {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                    </div>
                    <div class="text-xs text-slate-500">
                        {{ $activity->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Counterparty -->
        <div class="col-span-3">
            <div class="flex items-center space-x-2">
                <div class="relative px-2 py-2 rounded-full bg-gray-100 flex items-center justify-center">
                    <i class="fa-solid fa-circle-info text-slate-600 text-xs"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900 truncate">
                        {{ $activity->description }}
                    </div>
                    <div class="text-xs text-slate-500 truncate">
                        {{ $activity->metadata['account_no'] ?? "Missing" }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Reference No. -->
        <div class="col-span-2">
            <div class="text-sm font-medium text-slate-900">
                {{ $activity->reference_number }}
            </div>
        </div>

        <!-- Status -->
        <div class="col-span-2">
            <div class="inline-block">
                <div class="flex items-center space-x-2 rounded-full px-3 py-1 {{ $activity->status === 'completed' ? 'bg-green-100' :
                    ($activity->status === 'pending' ? 'bg-yellow-100' :
                    ($activity->status === 'failed' ? 'bg-red-100' :
                    'bg-gray-500')) }}">
                    <div class="w-2 h-2 rounded-full
                        {{ $activity->status === 'completed' ? 'bg-green-500' :
                        ($activity->status === 'pending' ? 'bg-yellow-500' :
                        ($activity->status === 'failed' ? 'bg-red-500' :
                        'bg-gray-500')) }}"></div>
                    <span class="text-sm font-medium
                        {{ $activity->status === 'completed' ? 'text-green-700' :
                        ($activity->status === 'pending' ? 'text-yellow-700' :
                        ($activity->status === 'failed' ? 'text-red-700' :
                        'text-gray-700')) }}">
                        {{ ucfirst($activity->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Amount -->
        <div class="col-span-2 text-right">
            @if($activity->amount)
                <div class="text-sm font-semibold text-slate-900">
                    {{ number_format($activity->amount, 2) }} {{ $activity->currency_symbol }}
                </div>
                @if(isset($activity->metadata['usd_value']))
                    <div class="text-xs text-slate-500">
                        ${{ number_format($activity->metadata['usd_value'], 2) }} USD
                    </div>
                @endif
            @else
                <div class="text-sm text-slate-400">-</div>
            @endif
        </div>
    </div>
</div> 