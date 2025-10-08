<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StoreController;
Route::post('store/add', [StoreController::class, 'store']);
Route::get('get/stores', [StoreController::class, 'getStores']);
Route::get('get/stores/{id}', [StoreController::class, 'storeList']);