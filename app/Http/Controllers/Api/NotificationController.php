<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification as NotificationModel;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
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

    // Extract all booking_ids from data TEXT
    $bookingIds = [];
    foreach ($notifications as $n) {
        $json = json_decode($n->data, true);
        if ($json && isset($json['booking_id'])) {
            $bookingIds[] = $json['booking_id'];
        }
    }

    // Load bookings + bikes
    $bookings = Booking::whereIn('id', $bookingIds)
        ->with('bike')   // booking belongsTo bike
        ->get()
        ->keyBy('id');   // easy access

    // Merge back into notifications
    $results = $notifications->map(function ($n) use ($bookings) {
        $json = json_decode($n->data, true);

        $bookingId = $json['booking_id'] ?? null;

        $booking = $bookingId ? $bookings->get($bookingId) : null;
        $bike = $booking?->bike;

        return [
            'id' => $n->id,
            'user_id' => $n->user_id,
            'type' => $n->type,
            'title' => $n->title,
            'message' => $n->message,
            'is_read' => $n->is_read,
            'created_at' => $n->created_at,
            'updated_at' => $n->updated_at,

            // Booking info
            'booking_id' => $booking?->id,
            'booking_status' => $booking?->status,
            'start_date' => $booking?->start_date,
            'end_date' => $booking?->end_date,
            'total_price' => $booking?->total_price,

            // Bike info
            'bike_title' => $bike?->title,
            'bike_no' => $bike?->bike_no,
            'bike_price' => $bike?->price,
            'bike_photo' => $bike?->photo,
        ];
    });

    return response()->json($results);
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
