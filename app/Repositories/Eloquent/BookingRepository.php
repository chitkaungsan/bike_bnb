<?php

namespace App\Repositories\Eloquent;

use App\Models\Bike;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
                'error' => 'Invalid data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createBooking($data)
    {
        // update user phone if different
        $user = User::find($data['user_id']);
        $phone = $data['user_phone'];

        if ($user->phone == null) {
            $user->phone = $data['user_phone'];
        }

        $user->save();

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

    public function findById($id)
    {
        return Booking::select(
            'bookings.id as booking_id',
            'bookings.bike_id',
            'bookings.rider_id',
            'bookings.start_date',
            'bookings.end_date',
            'bookings.status',
            'bookings.name as renter_name',
            'bookings.email as renter_email',
            'bookings.phone as renter_phone',
            'bookings.days',
            'bookings.daily_rate',
            'bookings.total_price',
            'bookings.payment_type',
            'bikes.title as bike_title',
            'bikes.price as bike_price',
            'bikes.photo as bike_photo',
            'stores.name as store_name',
            'stores.phone as store_phone',
            'stores.address as store_address',
            'stores.logo as store_logo',
            'users.name as user_name',
            'users.email as user_email',
            'users.phone as user_phone',
            'bookings.created_at'
        )
            ->leftJoin('bikes', 'bookings.bike_id', '=', 'bikes.id')
            ->leftJoin('stores', 'bikes.store_id', '=', 'stores.id')
            ->leftJoin('users', 'bookings.rider_id', '=', 'users.id')
            ->where('bookings.id', $id)
            ->first();
    }
    public function findByBikeId($bikeId)
    {
        return Booking::where('bike_id', $bikeId)
            ->select('start_date as start', 'end_date as end')
            ->get();
    }
    public function getBookingsByUserId($userId)
    {
        return DB::table('bookings')
            ->select(
                'bookings.id',
                'bookings.start_date',
                'bookings.end_date',
                'bookings.status',
                'bookings.total_price',
                'bookings.days',
                'bookings.daily_rate',
                'bookings.payment_type',
                'bikes.id as bike_id',
                'bikes.title as bike_title',
                'bikes.photo as bike_photo',
                'bikes.price as bike_price',
                'bikes.model as bike_model',
                'bikes.year as bike_year',
                'stores.id as store_id',
                'stores.name as store_name',
                'stores.location as store_location',
                'stores.phone as store_phone',
                'stores.logo as store_logo',
                'owners.id as owner_id',
                'owners.name as owner_name',
                'owners.phone as owner_phone'
            )
            ->leftJoin('bikes', 'bookings.bike_id', '=', 'bikes.id')
            ->leftJoin('stores', 'bikes.store_id', '=', 'stores.id')
            ->leftJoin('users as owners', 'bikes.user_id', '=', 'owners.id')
            ->where('bookings.rider_id', $userId)
            ->orderByDesc('bookings.created_at')
            ->get();
    }
    public function getBookingsByOwnerId($ownerId)
    {
        return DB::table('bookings')
            ->select(
                'bookings.id',
                'bookings.start_date',
                'bookings.end_date',
                'bookings.status',
                'bookings.total_price',
                'bookings.days',
                'bookings.daily_rate',
                'bookings.payment_type',
                'bookings.status as status',
                'bookings.created_at as created_at',
                'bikes.id as bike_id',
                'bikes.title as bike_title',
                'bikes.photo as bike_photo',
                'bikes.price as bike_price',
                'bikes.model as bike_model',
                'bikes.year as bike_year',
                'stores.id as store_id',
                'stores.name as store_name',
                'stores.location as store_location',
                'stores.phone as store_phone',
                'stores.logo as store_logo',
                'riders.id as rider_id',
                'riders.name as rider_name',
                'riders.phone as rider_phone'
            )
            ->leftJoin('bikes', 'bookings.bike_id', '=', 'bikes.id')
            ->leftJoin('stores', 'bikes.store_id', '=', 'stores.id')
            ->leftJoin('users as riders', 'bookings.rider_id', '=', 'riders.id')
            ->where('bikes.user_id', $ownerId)
            ->orderByDesc('bookings.created_at')
            ->get();
    }
    public function OwnerBookingById($id)
    {
        return DB::table('bookings')
            ->select(
                'bookings.id',
                'bookings.start_date',
                'bookings.end_date',
                'bookings.status',
                'bookings.total_price',
                'bookings.days',
                'bookings.daily_rate',
                'bookings.payment_type',
                'bikes.id as bike_id',
                'bikes.title as bike_title',
                'bikes.photo as bike_photo',
                'bikes.price as bike_price',
                'bikes.model as bike_model',
                'bikes.year as bike_year',
                'stores.id as store_id',
                'stores.name as store_name',
                'stores.location as store_location',
                'stores.phone as store_phone',
                'stores.logo as store_logo',
                'riders.id as rider_id',
                'riders.name as rider_name',
                'riders.phone as rider_phone'
            )
            ->leftJoin('bikes', 'bookings.bike_id', '=', 'bikes.id')
            ->leftJoin('stores', 'bikes.store_id', '=', 'stores.id')
            ->leftJoin('users as riders', 'bookings.rider_id', '=', 'riders.id')
            ->where('bookings.id', $id)
            ->first();
    }
    public function confirmBooking($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->status = 'confirmed';
            $booking->payment_status = 'held';
            $booking->updated_at = now();
            $booking->save();
        }
        return response()->json(['message' => 'Booking confirmed successfully'], 200);
    }
    public function cancelBooking($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->status = 'cancelled';
            $booking->updated_at = now();
            $booking->save();
        }
        return response()->json(['message' => 'Booking cancelled successfully'], 200);
    }
    public function startUse($id)
    {
        $booking = Booking::find($id);

        if (! $booking) return false;

        $booking->status = 'in_use';
        $booking->updated_at = now();
        $booking->save();

        return true;
    }

    public function completeBooking($id)
    {
        $booking = Booking::find($id);

        if (! $booking) return false;

        $booking->status = 'completed';
        $booking->payment_status = 'released'; // add this line!
        $booking->updated_at = now();
        $booking->save();

        return true;
    }
}
