<?php 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Api\AuthController;


use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Http\Controllers\TestEventController;

Route::get('/fire-event', [TestEventController::class, 'fire']);
Route::get('/test-mail', function () {
    Mail::to('mlbb.com2024@gmail.com')->send(new TestMail());
    return response()->json(['message' => 'Mail sent!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test', function () {
    return ['message' => 'API working'];
});
// Protected routes (after login)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
Route::post('upload-avatar', [App\Http\Controllers\Api\AuthController::class, 'uploadAvatar']);
Route::post('bike/logo/upload', [App\Http\Controllers\Api\StoreController::class, 'uploadBikeLogo']);
Route::post('user/set/role', [App\Http\Controllers\Api\AuthController::class, 'setRole']);


Route::get('/auth/{provider}/redirect', [SocialLoginController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'callback']);

require __DIR__.'/api_store.php';
require __DIR__.'/api_bike.php';
require __DIR__.'/api_home.php';
require __DIR__.'/api_booking.php';
require __DIR__.'/api_city.php';
require __DIR__.'/api_bike_filter.php';