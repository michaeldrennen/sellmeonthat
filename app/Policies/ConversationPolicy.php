<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Determine if the user can view the conversation.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        // User can view if they are the consumer or the business owner
        return $user->id === $conversation->user_id ||
               ($user->businessProfile && $user->businessProfile->id === $conversation->business_profile_id);
    }

    /**
     * Determine if the user can send messages in the conversation.
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        return $this->view($user, $conversation);
    }

    /**
     * Determine if the user can delete the conversation.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        // Only the want owner can delete conversations
        return $user->id === $conversation->user_id;
    }
}
