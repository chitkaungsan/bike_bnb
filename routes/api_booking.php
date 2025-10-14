<?php

use App\Http\Controllers\Api\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('calculate/price', [BookingController::class, 'calculatePrice']);