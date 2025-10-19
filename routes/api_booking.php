<?php

use App\Http\Controllers\Api\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('calculate/price', [BookingController::class, 'calculatePrice']);
Route::post('booking/create', [BookingController::class, 'createBooking']);
Route::get('bookings/{id}', [BookingController::class, 'getBookingWithId']);
Route::get('bookings/bike/{bikeId}', [BookingController::class, 'getBookingsByBikeId']);
Route::get('my/bookings/{userId}', [BookingController::class, 'getBookingsByUserId']);
Route::get('/owner/bookings/{owner_id}', [BookingController::class, 'getOwnerBookings']);
Route::get('/owner/booking/{id}', [BookingController::class, 'getOwnerBookingById']);
Route::get('booking/confirm/{id}', [BookingController::class, 'confirmBooking']);
Route::get('booking/cancel/{id}', [BookingController::class, 'cancelBooking']);
Route::post('/bookings/{id}/in-use', [BookingController::class, 'startUse']);

