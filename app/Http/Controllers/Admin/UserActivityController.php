<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivity::with(['user', 'subject']);

        // Filter by activity type if provided
        if ($request->filled('activity_type')) {
            $query->where('activity_type', $request->activity_type);
        }

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by reference number or user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $activities = $query->latest()->paginate(20);

        return view('admin.activities.index', compact('activities'));
    }

    public function show(UserActivity $activity)
    {
        $activity->load(['user', 'subject']);
        
        return $this->redirectToSubjectShow($activity);
    }

    /**
     * Redirect to the appropriate show page based on activity type
     */
    private function redirectToSubjectShow(UserActivity $activity)
    {
        return match($activity->activity_type) {
            UserActivity::TYPE_ASSET_TRANSFER => redirect()->route('admin.transfers.show', $activity->subject_id),
            UserActivity::TYPE_OTC_TRADE => redirect()->route('admin.otc.show', $activity->subject_id),
            default => back()->with('error', 'Unable to view details for this activity type.')
        };
    }
} 