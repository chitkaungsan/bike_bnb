<?php
use App\Http\Controllers\Api\BikeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::post('bike/store', [BikeController::class, 'bikeStore']);
Route::get('bike/brands', [BikeController::class, 'getBrands']);
Route::get('bike/categories', [BikeController::class, 'getCategories']);
Route::get('get/bikes/{user_id}', [BikeController::class, 'getBikeList']);
Route::get('get/bike/all', [BikeController::class, 'getBikes']);
Route::get('bikes/{id}', [BikeController::class, 'getBikeWithId']);
Route::get('get/bike/all/{id}', [BikeController::class, 'getAllBikesWithId']);
Route::get('get/all/categories', [BikeController::class, 'getAllCategories']);

