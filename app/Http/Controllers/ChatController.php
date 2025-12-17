<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Conversation;
use App\Services\ChatService;


class ChatController extends Controller
{
use AuthorizesRequests;
public function __construct(
    private ChatService $chatService
){}

public function index(Request $request)
{
    $conversations = $this->chatService->getUserConversations($request->user());

    return response()->json($conversations);
}

public function store(Request $request)
{
    $data = $request->validate([
        'type' => 'required|in:direct,group,course',
        'user_ids' => 'required_if:type,direct,group|array',
        'user_ids.*' => 'exists:users,id',
        'course_id' => 'required_if:type,course|exists:courses,id',
        'name' => 'nullablee|string|max:255',
   
    ]);

    $conversation = $this->chatService->createConversation($request->user(), $data);

    return response()->json($conversation);
}

public function show(Conversation $conversation, Request $request)
{
$this->authorize('view', $conversation);

$messages = $this->chatService->getConversationMessages($conversation);

return response()->json([
    'conversation' => $conversation,
    'messages' => $messages,
]);
}
}
