<?php

namespace App\Services;

use App\Actions\Chat\CreateConversationAction;
use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ConversationRepository;

class ChatService
{
    public function __construct(
        private CreateConversationAction $createConversationAction,
        private ConversationRepository $conversationRepository
    ){}

    public function getUserConversations(User $user)
    {
        return $this->conversationRepository->getUserConversations($user);
    }

    public function createConversation(User $user, array $data): Conversation
    {
        return $this->createConversationAction->execute($user, $data);
    }

    public function getConversationMessages(Conversation $conversation , int $perPage=50)
    {
        return $this->conversationRepository->getMessages($conversation, $perPage);
    }
}