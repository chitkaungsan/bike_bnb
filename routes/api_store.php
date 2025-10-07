<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::post('store/add', [App\Http\Controllers\Api\StoreController::class, 'store']);
Route::get('get/stores', [App\Http\Controllers\Api\StoreController::class, 'getStores']);