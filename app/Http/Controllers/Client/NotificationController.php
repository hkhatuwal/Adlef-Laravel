<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{


    public function markAsRead(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();
        return response()->json(['success' => true]);
    }
}
