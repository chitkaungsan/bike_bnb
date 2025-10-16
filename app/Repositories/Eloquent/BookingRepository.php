<?php

namespace App\Repositories\Eloquent;

use App\Models\Bike;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    public function calculateRentalPrice($bikeId, $startDate, $endDate)
    {
        try {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);

            // Normalize both to start of day (00:00:00)
            $start->setTime(0, 0, 0);
            $end->setTime(0, 0, 0);

            // Validate date order
            if ($end < $start) {
                return response()->json([
                    'error' => 'End date cannot be before start date.',
                ], 400);
            }

            // Inclusive difference
            $interval = $start->diff($end);
            $days = $interval->days + 1; // count both pickup and return

            // Get bike price
            $bike = Bike::findOrFail($bikeId);
            $ratePerDay = (float) $bike->price;

            $totalPrice = $days * $ratePerDay;

            return response()->json([
                'days' => $days,
                'price_per_day' => $ratePerDay,
                'total_price' => $totalPrice,
                'currency' => $bike->currency ?? 'THB',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Invalid data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function createBooking($data)
    {
        return Booking::create([
            'bike_id' => $data['bike_id'],
            'rider_id' => $data['user_id'],
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'phone' => $data['user_phone'],
            'daily_rate' => $data['daily_rate'],
            'days' => $data['days'],
            'payment_type' => $data['payment_method'],
            'total_price' => $data['totalPrice'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'status' => 'pending',
        ]);
    }

    public function isBikeAvailable($bikeId, $startDate, $endDate)
    {
        return ! Booking::where('bike_id', $bikeId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($q) use ($startDate, $endDate) {
                        $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                    });
            })
            ->exists();
    }
}
