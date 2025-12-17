<?php

namespace App\Services;

use App\Actions\Chat\MarkMessageAsReadAction;
use App\Models\Message;
use App\Models\User;
use App\Actions\Chat\SendMessageAction;
use App\Models\Conversation;
use Illuminate\Http\UploadedFile;
use App\Events\UserTyping;

class MessageService
{
    private function __construct(
        private SendMessageAction $sendMessageAction,
        private MarkMessageAsReadAction $markMessageAsReadAction
    ){}

    public function sendMessage(User $sender, Conversation $conversation, ?string $body, ?UploadedFile $attachment): Message
    {
        return $this->sendMessageAction->execute($sender, $conversation, $body, $attachment);
    }

    public function markAsRead(User $user, Message $message): void
    {
        $this->markMessageAsReadAction->execute($user, $message);
    }

    public function broadcastTyping(Conversation $conversation, User $user): void
    {
        broadcast(new UserTyping($conversation->id, $user))->toOthers();
    }
}