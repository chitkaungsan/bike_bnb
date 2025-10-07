<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::post('store/add', [App\Http\Controllers\Api\StoreController::class, 'store']);
