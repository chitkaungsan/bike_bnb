<?php

namespace App\Repositories\Eloquent;

use App\Models\Bike;
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
                'error' => 'End date cannot be before start date.'
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
            'currency' => $bike->currency ?? 'THB'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Invalid data: ' . $e->getMessage()
        ], 500);
    }
}

}
