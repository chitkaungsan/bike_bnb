<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification as NotificationModel;

class NotificationController extends Controller
{
    /**
     * Return all notifications for the logged-in user
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = NotificationModel::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($notifications);
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();

        $notification = NotificationModel::where('user_id', $user->id)
            ->findOrFail($id);

        $notification->update(['is_read' => true]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Mark all as read (optional)
     */
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        NotificationModel::where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'all_read']);
    }
}
