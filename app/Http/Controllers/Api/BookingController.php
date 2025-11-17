<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBookingRequest;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepositoryInterface $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function calculatePrice(Request $request)
    {
        $request->merge([
            'bike_id' => $request->query('bikeId'),
            'start_date' => $request->query('startDate'),
            'end_date' => $request->query('endDate'),
        ]);

        $request->validate([
            'bike_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $bikeId = $request->bike_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $bikeId = $request->input('bike_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $res = $this->bookingRepository->calculateRentalPrice($bikeId, $startDate, $endDate);

        return $res;
    }

    public function createBooking(CreateBookingRequest $request)
    {
        $data = $request->validated();

        // Convert selectedDates to Y-m-d
        $startDate = date('Y-m-d', strtotime($data['selectedDates'][0]));
        $endDate = date('Y-m-d', strtotime($data['selectedDates'][1]));

        // Check bike availability
        if (! $this->bookingRepository->isBikeAvailable($data['bike_id'], $startDate, $endDate)) {
            return response()->json([
                'error' => 'This bike is already booked for the selected dates.',
            ], 422);
        }

        // Prepare data for repository
        $bookingData = array_merge($data, [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $booking = $this->bookingRepository->createBooking($bookingData);

        return response()->json([
            'message' => 'Booking created successfully',
            'booking' => $booking,
        ], 201);
    }
    public function getBookingWithId($id)
    {
        $booking = $this->bookingRepository->findById($id);
        if (! $booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        return response()->json($booking);
    }
    public function getBookingsByBikeId($bikeId)
    {
        $bookings = $this->bookingRepository->findByBikeId($bikeId);

        return response()->json($bookings);
    }
    public function getBookingsByUserId($userId)
    {
        $bookings = $this->bookingRepository->getBookingsByUserId($userId);

        return response()->json($bookings);
    }
    public function getOwnerBookings($owner_id)
    {
        $bookings = $this->bookingRepository->getBookingsByOwnerId($owner_id);

        return response()->json($bookings);
    }
    public function getOwnerBookingById($id)
    {
        $booking = $this->bookingRepository->OwnerBookingById($id);
        if (! $booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }
        return response()->json($booking);
    }
    public function confirmBooking($id)
    {
        $booking = $this->bookingRepository->findById($id);
        if (! $booking) {
            return response()->json(['error' => 'Booking not found'], 404); // 404 Not Found    
        }
        $res = $this->bookingRepository->confirmBooking($id);
        return response()->json(['message' => 'Booking confirmed successfully'], 200);
    }
    public function cancelBooking($id)
    {
        $booking = $this->bookingRepository->findById($id);
        if (! $booking) {
            return response()->json(['error' => 'Booking not found'], 404); // 404 Not Found    
        }
        $this->bookingRepository->cancelBooking($id);
        return response()->json(['message' => 'Booking canceled successfully'],);
    }
    public function startUse($id)
    {
        $booking = Booking::find($id);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        if ($booking->status !== 'confirmed') {
            return response()->json(['message' => 'Only confirmed bookings can start use'], 400);
        }
        if ($this->bookingRepository->startUse($id)) {
            return response()->json(['message' => 'Booking marked as In Use successfully'], 200);
        }
        return response()->json(['message' => 'Booking not found'], 404);
    }
    public function completeBooking($id)
    {
        $booking = Booking::find($id);
        if (! $booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        if ($booking->status !== 'in_use') {
            return response()->json(['message' => 'Only bookings in use can be completed'], 400);
        }
        if ($this->bookingRepository->completeBooking($id)) {
            return response()->json(['message' => 'Booking marked as In Use successfully'], 200);
        }
        return response()->json(['message' => 'Booking not found'], 404);
    }
}
