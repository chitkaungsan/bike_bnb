<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\BookingInUse;
use App\Notifications\BookingComplete;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatus extends Command
{
    protected $signature = 'bookings:update-status';
    protected $description = 'Auto update bookings from confirmed → in_use and in_use → completed';

    public function handle()
    {
        $inUseCount = 0;
        $completedCount = 0;

        // Step 1: confirmed → in_use
        Booking::where('status', 'confirmed')
            ->whereDate('start_date', '<=', now())
            ->chunk(10, function ($bookings) use (&$inUseCount) {
                foreach ($bookings as $booking) {
                    try {
                        $booking->update([
                            'status' => 'in_use',
                            'updated_at' => now(),
                        ]);

                        // Send to rider
                        if ($booking->email) {
                            Notification::route('mail', $booking->email)
                                ->notify(new BookingInUse($booking));
                        }

                        // Send to owner
                        $ownerEmail = DB::table('bikes')
                            ->join('users', 'bikes.user_id', '=', 'users.id')
                            ->where('bikes.id', $booking->bike_id)
                            ->value('users.email');

                        if ($ownerEmail) {
                            Notification::route('mail', $ownerEmail)
                                ->notify(new BookingInUse($booking));
                        }

                        $inUseCount++;
                    } catch (\Throwable $e) {
                        Log::error("Failed to update booking {$booking->id} to in_use: " . $e->getMessage());
                    }
                }
            });

        // Step 2: in_use → completed
        Booking::where('status', 'in_use')
            ->whereDate('end_date', '<=', now())
            ->chunk(10, function ($bookings) use (&$completedCount) {
                foreach ($bookings as $booking) {
                    try {
                        $booking->update([
                            'status' => 'completed',
                            'payment_status' => 'released',
                            'updated_at' => now(),
                        ]);

                        // Send to rider
                        if ($booking->email) {
                            Notification::route('mail', $booking->email)
                                ->notify(new BookingComplete($booking));
                        }

                        // Send to owner
                        $ownerEmail = DB::table('bikes')
                            ->join('users', 'bikes.user_id', '=', 'users.id')
                            ->where('bikes.id', $booking->bike_id)
                            ->value('users.email');

                        if ($ownerEmail) {
                            Notification::route('mail', $ownerEmail)
                                ->notify(new BookingComplete($booking));
                        }

                        $completedCount++;
                    } catch (\Throwable $e) {
                        Log::error("Failed to update booking {$booking->id} to completed: " . $e->getMessage());
                    }
                }
            });

        $this->info("Updated: {$inUseCount} bookings to in_use, {$completedCount} bookings to completed.");
    }
}
