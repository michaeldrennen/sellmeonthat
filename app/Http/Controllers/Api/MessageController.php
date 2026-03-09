<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display messages for a conversation.
     */
    public function index(Request $request, Conversation $conversation)
    {
        // Check if user is part of this conversation
        $user = $request->user();
        $isParticipant = $user->id === $conversation->user_id ||
                        ($user->businessProfile && $user->businessProfile->id === $conversation->business_profile_id);

        if (!$isParticipant) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = $conversation->messages()->with('sender')->latest();

        $messages = $query->paginate($request->get('per_page', 50));

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request, Conversation $conversation)
    {
        // Check if user is part of this conversation
        $user = $request->user();
        $isParticipant = $user->id === $conversation->user_id ||
                        ($user->businessProfile && $user->businessProfile->id === $conversation->business_profile_id);

        if (!$isParticipant) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $user->id,
            'body' => $validated['body'],
        ]);

        // Update conversation's last_message_at
        $conversation->update(['last_message_at' => now()]);

        // Send notification to recipient
        $recipient = $user->id === $conversation->user_id
            ? $conversation->businessProfile->user
            : $conversation->user;

        $recipient->notify(new \App\Notifications\NewMessageNotification($message, $conversation));

        return new MessageResource($message->load('sender'));
    }

    /**
     * Mark a message as read.
     */
    public function markAsRead(Request $request, Message $message)
    {
        // Check if user is recipient (not sender)
        $user = $request->user();
        $conversation = $message->conversation;

        $isRecipient = ($user->id === $conversation->user_id && $message->sender_id !== $user->id) ||
                      ($user->businessProfile &&
                       $user->businessProfile->id === $conversation->business_profile_id &&
                       $message->sender_id !== $user->id);

        if (!$isRecipient) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message->markAsRead();

        return new MessageResource($message->load('sender'));
    }
}
