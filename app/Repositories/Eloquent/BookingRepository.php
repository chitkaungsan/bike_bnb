<?php

namespace App\Repositories\Eloquent;


use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Models\Bike;
class BookingRepository implements BookingRepositoryInterface
{
    public function calculateRentalPrice($bikeId, $startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);
        $days = $interval->days + 1; 
        $ratePerDay = Bike::find($bikeId)->price;

        return response()->json(['days' => $days,'price_per_day' => $ratePerDay, 'total_price' => $days * $ratePerDay]);
    }
}
