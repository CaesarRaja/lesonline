<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('schedule.{mentorId}', function ($user, $mentorId) {
    return $user->id === (int) $mentorId || $user->isAdmin();
});

Broadcast::channel('chat.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
