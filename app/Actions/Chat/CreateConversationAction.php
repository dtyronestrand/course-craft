<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Models\User;
use App\Repositories\ConversationRepository;

class CreateConversationAction
{
    public function __construct(
        private ConversationRepository $conversationRepository
    ) {}

    public function execute(User $user, array $data): Conversation
    {
        if($data['type'] === 'direct' ){
            $existing = $this->conversationRepository->findDirectConversation($user, $data['user_ids'][0]);
            if($existing){
                return $existing->load(['participants', 'latestMessage']);
            }
        }

        $conversation = $this->conversationRepository->create([
            'type' => $data['type'],
            'name' => $data['name'] ?? null,
            'course_id' => $data['course_id'] ?? null,
        ]);

        $userIds = array_merge([$user->id], $data['user_ids'] ?? []);
        $conversation->participants()->attach($userIds);

        return $conversation->load(['participants', 'latestMessage']);
    }
}