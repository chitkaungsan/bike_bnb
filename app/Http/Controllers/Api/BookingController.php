<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;

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
    public function createBooking(Request $request)
    {
        $request->validate([
            'bike_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $data = $request->only(['bike_id', 'start_date', 'end_date', 'name', 'phone']);

        $booking = $this->bookingRepository->createBooking($data);

        return response()->json($booking, 201);
    }   
}