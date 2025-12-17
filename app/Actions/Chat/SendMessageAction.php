<?php

namespace App\Actions\Chat;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Notifications\NewMessageNotification;
use App\Repositories\MessageRepository;
use Illuminate\Http\UploadedFile;

class SendMessageAction
{
    public function __construct(
        private MessageRepository $messageRepository
    ) {}

    public function execute(
        User $user, 
        Conversation $conversation, 
        ?string $body, 
        ?UploadedFile $attachment
    ): Message {
        $data = [
            'conversation_id' => $conversation->id,
            'user_id' => $user->id,
            'body' => $body,
        ];

        if ($attachment) {
            $path = $attachment->store('chat-attachments', 'public');
            $data['attachment_path'] = $path;
            $data['attachment_name'] = $attachment->getClientOriginalName();
        }

        $message = $this->messageRepository->create($data);
        $conversation->touch();

        // Broadcast to conversation channel
        broadcast(new MessageSent($message->load(['user', 'reads'])))->toOthers();

        // Send notifications to other participants
        $participants = $conversation->participants()
            ->where('user_id', '!=', $user->id)
            ->get();

        foreach ($participants as $participant) {
            $participant->notify(new NewMessageNotification($message));
        }

        return $message->load(['user', 'reads']);
    }
}