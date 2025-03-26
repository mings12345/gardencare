<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PusherAuthController extends Controller
{
    public function authenticate(Request $request)
{
    $user = auth()->user(); // Ensure user is authenticated
    $channelName = $request->channel_name;

    // Validate channel access (e.g., gardener can only access their channel)
    if (str_starts_with($channelName, 'private-gardener.') && 
        $user->id == substr($channelName, strrpos($channelName, '.') + 1)) {
        
        return response()->json([
            'auth' => Pusher::authenticate(
                $channelName, 
                $request->socket_id
            )
        ]);
    }

    abort(403, 'Unauthorized');
}
}
