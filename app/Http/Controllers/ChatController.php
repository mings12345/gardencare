<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        $conversations = Conversation::with(['messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->with(['homeowner', 'gardener', 'serviceProvider'])
            ->forUser($user->id)
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function($conversation) {
                $conversation->load('otherParticipant');
                return $conversation;
            });
            
        return response()->json($conversations);
    }

    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $conversation->load(['homeowner', 'gardener', 'serviceProvider']);
        $conversation->markAsReadForUser(auth()->id());
        
        return response()->json($conversation);
    }

    public function messages(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $messages = $conversation->messages()
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return response()->json($messages);
    }

    public function storeMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        
        $request->validate([
            'message' => 'required|string|max:2000'
        ]);
        
        $user = $request->user();
        $senderType = $this->getUserType($user);
        
        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $request->message
        ]);
        
        $conversation->update(['last_message_at' => now()]);
        
        broadcast(new MessageSent($message))->toOthers();
        
        return response()->json($message, 201);
    }

    public function startConversation(Request $request)
    {
        $request->validate([
            'homeowner_id' => 'required_without_all:gardener_id,service_provider_id|exists:users,id',
            'gardener_id' => 'required_without_all:homeowner_id,service_provider_id|exists:users,id',
            'service_provider_id' => 'required_without_all:homeowner_id,gardener_id|exists:users,id',
        ]);
        
        $user = $request->user();
        $senderType = $this->getUserType($user);
        
        $conversation = Conversation::firstOrCreate(
            $this->getConversationCriteria($request, $user, $senderType),
            $this->getConversationAttributes($request, $user, $senderType)
        );
        
        $conversation->load(['homeowner', 'gardener', 'serviceProvider']);
        
        return response()->json($conversation, 201);
    }

    protected function getUserType(User $user)
    {
        switch($user->user_type) {
            case 'homeowner': return 'homeowner';
            case 'gardener': return 'gardener';
            default: return 'service_provider';
        }
    }

    protected function getConversationCriteria(Request $request, User $user, string $senderType)
    {
        $criteria = [];
        
        if ($senderType === 'homeowner') {
            $criteria['homeowner_id'] = $user->id;
            if ($request->gardener_id) {
                $criteria['gardener_id'] = $request->gardener_id;
            } else {
                $criteria['service_provider_id'] = $request->service_provider_id;
            }
        } elseif ($senderType === 'gardener') {
            $criteria['homeowner_id'] = $request->homeowner_id;
            $criteria['gardener_id'] = $user->id;
        } else {
            $criteria['homeowner_id'] = $request->homeowner_id;
            $criteria['service_provider_id'] = $user->id;
        }
        
        return $criteria;
    }

    protected function getConversationAttributes(Request $request, User $user, string $senderType)
    {
        $attributes = [];
        
        if ($senderType === 'homeowner') {
            $attributes['homeowner_id'] = $user->id;
            $attributes['gardener_id'] = $request->gardener_id;
            $attributes['service_provider_id'] = $request->service_provider_id;
        } elseif ($senderType === 'gardener') {
            $attributes['homeowner_id'] = $request->homeowner_id;
            $attributes['gardener_id'] = $user->id;
        } else {
            $attributes['homeowner_id'] = $request->homeowner_id;
            $attributes['service_provider_id'] = $user->id;
        }
        
        return $attributes;
    }
}