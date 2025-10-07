<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::post('bike/store', [App\Http\Controllers\Api\BikeController::class, 'bikeStore']);
