<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\User;

class ConversationRepository
{
    public function findWithDetails(int $conversationId)
    {
        return Conversation::with(['meages.user', 'participant'])->findOrFail($conversationId);
    }
    public function markAsRead(Conversation $conversation, User $user): void
    {
        $conversation->participants()->updateExistingPivot($user->id, ['last_read_at' => now()]);
    }

}