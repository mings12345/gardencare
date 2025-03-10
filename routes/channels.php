<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('private-gardener.{gardenerId}', function ($user, $gardenerId) {
    return (int) $user->id === (int) $gardenerId;
});

Broadcast::channel('private-serviceprovider.{serviceProviderId}', function ($user, $serviceProviderId) {
    return (int) $user->id === (int) $serviceProviderId;
});
