<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
class ConversationsController extends Controller
{
public function show(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('user')->latest()->get();
        return Inertia::render('conversations/Show', [
            'conversations' => ConversationResource::make($conversation),
            'messages' => MessageResource::collection($messages)
        ]);
    }
}
