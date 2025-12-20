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
        $conversation->load('participants');
        $messages = $conversation->messages()->with('user')->latest()->get();
        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages
        ]);
    }
}
