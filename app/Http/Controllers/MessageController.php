<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MessageController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private MessageService $messageService
    ) {}

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $request->validate([
            'body' => 'required_without:attachment|string',
            'attachment' => 'nullable|file|max:10240',
        ]);

        $message = $this->messageService->sendMessage(
            $request->user(),
            $conversation,
            $request->body,
            $request->file('attachment')
        );

        return response()->json($message);
    }

    public function markAsRead(Request $request, Message $message)
    {
        $this->messageService->markAsRead($request->user(), $message);
        
        return response()->json(['success' => true]);
    }

    public function typing(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $this->messageService->broadcastTyping($conversation, $request->user());

        return response()->json(['success' => true]);
    }
}