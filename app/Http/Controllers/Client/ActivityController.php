<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = UserActivity::query()
            ->where('user_id', $request->user()->id)
            ->latest();

        // Filter by activity type
        if ($request->filled('type')) {
            $query->ofType($request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->withStatus($request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->start_date));
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->end_date));
        }

        // Get paginated results with query string
        $activities = $query->paginate(15)->appends($request->query());

        return view('client.activity.index', compact('activities'));
    }
}
