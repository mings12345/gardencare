<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('private-gardener.{gardenerId}', function ($user, $gardenerId) {
    return (int) $user->id === (int) $gardenerId;
});

Broadcast::channel('private-serviceprovider.{serviceProviderId}', function ($user, $serviceProviderId) {
    return (int) $user->id === (int) $serviceProviderId;
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = \App\Models\Conversation::findOrFail($conversationId);
    
    return $conversation->homeowner_id == $user->id || 
           $conversation->gardener_id == $user->id || 
           $conversation->service_provider_id == $user->id;
});

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
