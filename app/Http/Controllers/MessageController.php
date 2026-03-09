<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created message.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = $request->user();

        // Check if user is part of this conversation
        $isParticipant = $user->id === $conversation->user_id ||
                        ($user->businessProfile && $user->businessProfile->id === $conversation->business_profile_id);

        if (!$isParticipant) {
            abort(403, 'You are not authorized to send messages in this conversation.');
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

        return redirect()->route('conversations.show', $conversation)
            ->with('success', 'Message sent successfully.');
    }
}
