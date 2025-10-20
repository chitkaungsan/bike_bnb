<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Booking;
use App\Notifications\BookingConfirmed;

Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app'); 
})->where('any', '.*');

Route::get('/owner/{any}', function () {
    return view('app'); 
})->where('any', '.*');

