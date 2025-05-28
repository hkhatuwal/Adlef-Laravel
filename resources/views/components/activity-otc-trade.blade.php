@props(['activity', 'loop'])

<!-- OTC Trade Activity Row -->
<div class="px-6 py-4 {{ !$loop->last ? 'border-b border-slate-100' : '' }} hover:bg-slate-50/50 transition-all duration-150">
    <div class="grid grid-cols-12 gap-4 items-center">
        <!-- Type & Date -->
        <div class="col-span-3">
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 bg-opacity-10 flex items-center justify-center">
                        <i class="fa-solid {{ $activity->metadata['icon_class'] ?? 'fa-exchange-alt' }} text-sm"></i>
                    </div>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900">
                       OTC
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
                <div class="relative px-2 py-2 rounded-full bg-purple-100 flex items-center justify-center">
                    <i class="fa-solid fa-handshake text-black text-xs"></i>
                    <!-- OTC Trade indicator -->
                    <i class="fa-solid fa-exchange-alt bg-black text-xs absolute -bottom-0.5 -right-0.5 text-white rounded-full p-0.5 border border-gray-200"
                       style="font-size: 8px;"></i>
                </div>
                <div>
                    <div class="text-sm font-medium text-slate-900 truncate">
                        {{ $activity->description }}
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
                @if(isset($activity->metadata['exchange_rate']))
                    <div class="text-xs text-purple-500">
                        Rate: {{ $activity->metadata['exchange_rate'] }}
                    </div>
                @endif
            @else
                <div class="text-sm text-slate-400">-</div>
            @endif
        </div>
    </div>
</div>
