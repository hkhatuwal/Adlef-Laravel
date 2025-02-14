<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AssetAccount;
use App\Models\UserActivity;
use App\Utils\CurrencyCalculator;
use App\Utils\FeeCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get user's asset accounts with their currencies
        $assetAccounts = AssetAccount::with('currency')
            ->where('user_id', $user->id)
            ->get();

        // Calculate portfolio statistics
        $totalValue = $assetAccounts->sum('balance');
        $assetClassesCount = $assetAccounts->groupBy('currency.type')->count();

        // Group assets by class (crypto/fiat)
        $assetClasses = $assetAccounts
            ->groupBy('currency.type')
            ->map(function($accounts, $type) use ($totalValue) {
                $value = $accounts->sum('balance');
                $percentage = $totalValue > 0 ? ($value / $totalValue) * 100 : 0;
                return [
                    'name' => ucfirst($type),
                    'value' => $value,
                    'percentage' => round($percentage, 1),
                    'color' => $type === 'crypto' ? 'bg-purple-500' : 'bg-blue-500',
                    'icon' => $type === 'crypto' ? 'currency_bitcoin' : 'payments',
                    'change_24h' => 0 // You would calculate this based on historical data
                ];
            })
            ->values();

        // Get latest activities for 24h change calculation
        $recentActivities = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDay())
            ->get();

        // Calculate 24h change
        $totalChange24h = $recentActivities
            ->where('status', UserActivity::STATUS_COMPLETED)
            ->sum(function($activity) {
                return match($activity->action) {
                    UserActivity::ACTION_DEPOSIT,
                    UserActivity::ACTION_TRANSFER_IN,
                    UserActivity::ACTION_BUY => $activity->amount,
                    UserActivity::ACTION_WITHDRAW,
                    UserActivity::ACTION_TRANSFER_OUT,
                    UserActivity::ACTION_SELL => -$activity->amount,
                    default => 0
                };
            });

        $portfolioStats = [
            'total_value' => $totalValue,
            'total_change_24h' => $totalChange24h,
            'total_change_24h_percentage' => $totalValue > 0 ? ($totalChange24h / $totalValue) * 100 : 0,
            'asset_classes' => $assetClassesCount,
            'total_assets' => $assetAccounts->count(),
            'last_updated' => now()
        ];

        // Prepare assets table headers
        $assetHeaders = [
            [
                'title' => 'Asset',
                'icon' => 'account_balance_wallet',
                'iconBg' => 'bg-indigo-50',
                'iconColor' => 'text-indigo-600'
            ],
            [
                'title' => 'Balance',
                'icon' => 'balance',
                'iconBg' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600'
            ],
            [
                'title' => 'Value (USD)',
                'icon' => 'attach_money',
                'iconBg' => 'bg-blue-50',
                'iconColor' => 'text-blue-600'
            ],
            [
                'title' => '24h Change',
                'icon' => 'trending_up',
                'iconBg' => 'bg-purple-50',
                'iconColor' => 'text-purple-600'
            ]
        ];

        // Prepare asset rows from real data
        $assetRows = $assetAccounts->map(function($account) {
            $currency = $account->currency;
            return [
                // Asset column with icon and name
                '<div class="flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 mr-3">
                        <img src="'.asset('storage/'.$currency->icon).'"
                             alt="'.$currency->symbol.'"
                             class="w-5 h-5">
                    </span>
                    <div>
                        <div class="font-medium text-slate-900">'.$currency->name.'</div>
                        <div class="text-xs text-slate-500">'.$currency->symbol.'</div>
                    </div>
                </div>',
                // Balance column
                '<div class="font-medium">'.number_format($account->balance, $currency->type === 'crypto' ? 8 : 2).' '.$currency->symbol.'</div>',
                // Value column
                '<div class="font-medium">$'.number_format(CurrencyCalculator::convertToUSD($account->balance,$currency)['converted_amount']).'</div>',
                // 24h Change column - You would calculate this based on historical data
                '<div class="flex items-center text-slate-600">
                    <i class="material-symbols-outlined text-base mr-1">trending_flat</i>
                    <span>0.00%</span>
                </div>'
            ];
        })->toArray();

        // Prepare activities table headers
        $activityHeaders = [
            [
                'title' => 'Activity',
                'icon' => 'history',
                'iconBg' => 'bg-indigo-50',
                'iconColor' => 'text-indigo-600'
            ],
            [
                'title' => 'Amount',
                'icon' => 'payments',
                'iconBg' => 'bg-emerald-50',
                'iconColor' => 'text-emerald-600'
            ],
            [
                'title' => 'Status',
                'icon' => 'check_circle',
                'iconBg' => 'bg-blue-50',
                'iconColor' => 'text-blue-600'
            ],
            [
                'title' => 'Date',
                'icon' => 'calendar_today',
                'iconBg' => 'bg-purple-50',
                'iconColor' => 'text-purple-600'
            ]
        ];

        // Get recent activities
        $activities = UserActivity::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Prepare activity rows from real data
        $activityRows = $activities->map(function($activity) {
            $iconMap = [
                UserActivity::TYPE_ASSET_TRANSFER => 'swap_horiz',
                UserActivity::TYPE_OTC_TRADE => 'currency_exchange',
                UserActivity::TYPE_CRYPTO_WITHDRAWAL => 'arrow_outward',
                UserActivity::TYPE_FIAT_WITHDRAWAL => 'payments',
                UserActivity::TYPE_DEPOSIT => 'add_circle'
            ];

            $bgColorMap = [
                UserActivity::TYPE_ASSET_TRANSFER => 'bg-indigo-50',
                UserActivity::TYPE_OTC_TRADE => 'bg-purple-50',
                UserActivity::TYPE_CRYPTO_WITHDRAWAL => 'bg-red-50',
                UserActivity::TYPE_FIAT_WITHDRAWAL => 'bg-red-50',
                UserActivity::TYPE_DEPOSIT => 'bg-emerald-50'
            ];

            $textColorMap = [
                UserActivity::TYPE_ASSET_TRANSFER => 'text-indigo-600',
                UserActivity::TYPE_OTC_TRADE => 'text-purple-600',
                UserActivity::TYPE_CRYPTO_WITHDRAWAL => 'text-red-600',
                UserActivity::TYPE_FIAT_WITHDRAWAL => 'text-red-600',
                UserActivity::TYPE_DEPOSIT => 'text-emerald-600'
            ];

            return [
                // Activity type column
                '<div class="flex items-center">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg '.$bgColorMap[$activity->activity_type].' '.$textColorMap[$activity->activity_type].' mr-3">
                        <i class="material-symbols-outlined">'.$iconMap[$activity->activity_type].'</i>
                    </span>
                    <div>
                        <div class="font-medium text-slate-900">'.str_replace('_', ' ', ucwords($activity->activity_type)).'</div>
                        <div class="text-xs text-slate-500">'.$activity->description.'</div>
                    </div>
                </div>',
                // Amount column
                '<div class="font-medium text-slate-900">'.number_format($activity->amount, 8).' '.$activity->currency_symbol.'</div>',
                // Status column
                '<div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.
                    ($activity->status === UserActivity::STATUS_COMPLETED ? 'bg-emerald-50 text-emerald-700' :
                    ($activity->status === UserActivity::STATUS_PENDING ? 'bg-amber-50 text-amber-700' :
                    'bg-red-50 text-red-700')).'">
                    <i class="material-symbols-outlined text-base mr-1">'.
                        ($activity->status === UserActivity::STATUS_COMPLETED ? 'check_circle' :
                        ($activity->status === UserActivity::STATUS_PENDING ? 'pending' :
                        'error')).'</i>
                    '.ucfirst($activity->status).'
                </div>',
                // Date column
                '<div class="text-sm text-slate-500">'.$activity->created_at->diffForHumans().'</div>'
            ];
        })->toArray();

        return view('client.dashboard', compact(
            'portfolioStats',
            'assetClasses',
            'assetHeaders',
            'assetRows',
            'activityHeaders',
            'activityRows'
        ));
    }
}
