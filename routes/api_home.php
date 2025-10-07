<?php 
use Illuminate\Support\Facades\Route;

Route::get('home/bikes', [App\Http\Controllers\Api\HomePageController::class, 'index']);