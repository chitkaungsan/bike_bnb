<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class BookingStatusChange implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $ownerId;

    /**
     * Create a new event instance.
     */
    public function __construct(Booking $booking, $ownerId)
    {
        $this->booking = $booking;
        $this->ownerId = $ownerId;
    }

    /**
     * The channel this event should broadcast to.
     */
    public function broadcastOn()
    {
        // 🎯 Owner channel
        if ($this->ownerId) {
            return new PrivateChannel('owner-booking-status.' . $this->ownerId);
        }

        // 🎯 Rider channel
        if ($this->booking->rider_id) {
            return new PrivateChannel('user-booking-status.' . $this->booking->rider_id);
        }

        return [];
    }

    /**
     * Broadcast name (so frontend can listen like `.BookingStatusChange`)
     */
    public function broadcastAs()
    {
        return 'BookingStatusChange';
    }

    /**
     * Data sent to frontend
     */
    public function broadcastWith()
    {
        return [
            'booking_id' => $this->booking->id,
            'status' => $this->booking->status,
            'bike_id' => $this->booking->bike_id,
            'owner_id' => $this->ownerId,
            'message' => $this->booking->status === 'pending'
                ? 'You have a new booking request.'
                : 'Booking status updated: ' . $this->booking->status,
        ];
    }
}
