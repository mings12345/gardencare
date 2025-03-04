<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('gardener.{gardenerId}', function ($user, $gardenerId) {
    return (int) $user->id === (int) $gardenerId;
});

