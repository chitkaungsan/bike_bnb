<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app'); // Assuming your main blade file is app.blade.php
})->where('any', '.*');

