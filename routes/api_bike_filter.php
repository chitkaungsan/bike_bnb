<?php 
use App\Http\Controllers\Api\BikeFilterController;
use Illuminate\Support\Facades\Route;

Route::get('filter/bikes', [BikeFilterController::class, 'bikeFilter']);
Route::get('search/bikes/filter', [BikeFilterController::class, 'searchBikesFilter']);