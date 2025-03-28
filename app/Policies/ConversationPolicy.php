<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Conversation $conversation)
    {
        return $conversation->homeowner_id == $user->id || 
               $conversation->gardener_id == $user->id || 
               $conversation->service_provider_id == $user->id;
    }

    public function create(User $user)
    {
        return true;
    }
}
