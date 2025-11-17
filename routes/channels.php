<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('owner-booking-status.{ownerId}', function ($user, $ownerId) {
    // ✅ Only owner can listen to their channel
    return (int) $user->id === (int) $ownerId;
});

Broadcast::channel('user-booking-status.{userId}', function ($user, $userId) {
    // ✅ Only rider can listen to their channel
    return (int) $user->id === (int) $userId;
});
