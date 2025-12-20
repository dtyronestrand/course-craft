<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ConversationRepository
{
    public function getUserConversations(User $user)
    {
        return $user->conversations()
        ->with(['latestMessage', 'participants'])
        ->withCount(['messages as unread_count' => function($query) use ($user) {
            $query->where('user_id', '!=', $user->id)
            ->whereRaw('created_at > COALESCE((SELECT read_at FROM conversation_participants WHERE conversation_id = messages.conversation_id AND user_id = ?), "1970-01-01")', [$user->id]);
        }])
        ->latest('updated_at')
        ->get();
    }

    public function findDirectConversation(User $user, int $otherUserId)
    {
        return Conversation::where('type', 'direct')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function ($query) use ($otherUserId) {
                $query->where('user_id', $otherUserId);
            })
            ->first();
    }

    public function create(array $data): Conversation
    {
        return Conversation::create($data);
    }

    public function getMessages(Conversation $conversation, int $perPage=50){
        return $conversation->messages()
        ->with(['user', 'reads'])
        ->latest()
        ->paginate($perPage);
    }
}