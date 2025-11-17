<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Support\Facades\DB;

class BookingStatusChange implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;
    public $ownerId;

    public function __construct(Booking $booking, $ownerId)
    {
        $this->booking = $booking;
        $this->ownerId = $ownerId;
    }

    public function broadcastOn()
    {
        if ($this->ownerId) {
            return new PrivateChannel('owner-booking-status.' . $this->ownerId);
        }

        if ($this->booking->rider_id) {
            return new PrivateChannel('user-booking-status.' . $this->booking->rider_id);
        }

        return [];
    }

    public function broadcastAs()
    {
        return 'BookingStatusChange';
    }

    public function broadcastWith()
    {

        $message = match($this->booking->status) {
            'pending'   => 'New booking request received.',
            'confirmed' => 'Your booking has been confirmed.',
            'cancelled' => 'Your booking has been cancelled.',
            'in_use'    => 'The booking is now in use.',
            'completed' => 'The booking has been completed.',
            default     => 'Booking status updated.',
        };

        return [
            'message' => $message,
        ];
    }
}
