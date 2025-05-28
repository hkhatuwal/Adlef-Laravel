@props(['activity', 'loop'])

{{-- Activity Renderer Component - Routes to specific activity type components --}}
@switch($activity->activity_type)
    @case(\App\Models\UserActivity::TYPE_ASSET_TRANSFER)
        <x-activity-asset-transfer :activity="$activity" :loop="$loop" />
        @break
    @case(\App\Models\UserActivity::TYPE_OTC_TRADE)
        <x-activity-otc-trade :activity="$activity" :loop="$loop" />
        @break
    {{-- Add new activity types here --}}
    {{-- @case(\App\Models\UserActivity::TYPE_CRYPTO_WITHDRAWAL)
        <x-activity-crypto-withdrawal :activity="$activity" :loop="$loop" />
        @break --}}
    {{-- @case(\App\Models\UserActivity::TYPE_FIAT_WITHDRAWAL)
        <x-activity-fiat-withdrawal :activity="$activity" :loop="$loop" />
        @break --}}
    @default
        <x-activity-default :activity="$activity" :loop="$loop" />
@endswitch 