<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

// Group routes under auth middleware
Route::middleware('auth:api')->group(function () {
Route::get('owner/notifications', [NotificationController::class, 'index']);
    Route::post('owner/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::post('owner/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
});
