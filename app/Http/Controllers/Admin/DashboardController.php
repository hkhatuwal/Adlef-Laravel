<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use App\Models\AssetTransfer;
use App\Models\OtcRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total users count
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();

        // Get users registered in last 7 days for chart
        $userRegistrationData = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Calculate Revenue (fees + network_fees)
        $assetTransferRevenue = AssetTransfer::sum('fee');
        $otcRequestRevenue = OtcRequest::sum('network_fee');
        $totalRevenue = $assetTransferRevenue + $otcRequestRevenue;

        // Calculate Costs
        $assetTransferCosts = AssetTransfer::sum('transaction_cost');
        $otcRequestCosts = OtcRequest::sum('transaction_cost');
        $totalCosts = $assetTransferCosts + $otcRequestCosts;

        // Calculate Profit
        $totalProfit = $totalRevenue - $totalCosts;

        // Get activity metrics
        $totalActivities = UserActivity::count();
        $pendingActivities = UserActivity::where('status', 'pending')->count();
        $completedActivities = UserActivity::where('status', 'completed')->count();
        $failedActivities = UserActivity::where('status', 'failed')->count();

        // Get activities by type for pie chart
        $activitiesByType = UserActivity::select('activity_type', DB::raw('count(*) as count'))
            ->groupBy('activity_type')
            ->get();

        // Get recent activities
        $recentActivities = UserActivity::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Get verification stats
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = $totalUsers - $verifiedUsers;

        // Calculate verification rate
        $verificationRate = $totalUsers > 0 ? ($verifiedUsers / $totalUsers) * 100 : 0;


        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday',
            'userRegistrationData',
            'totalActivities',
            'pendingActivities',
            'completedActivities',
            'failedActivities',
            'activitiesByType',
            'recentActivities',
            'verifiedUsers',
            'unverifiedUsers',
            'verificationRate',
            'totalRevenue',
            'totalCosts',
            'totalProfit'
        ));
    }
}
