<?php

namespace App\Actions\Chat;

use App\Models\Message;
use App\Models\User;
use App\Repositories\MessageRepository;
use App\Events\MessageRead;

class MarkMessageAsReadAction
{
    public function __construct(
        private MessageRepository $messageRepository
    ){}

    public function execute(User $user, Message $message): void
    {
        $this->messageRepository->markAsRead($message, $user->id);
        broadcast(new MessageRead($message, $user))->toOthers();
    }
}