<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ConversationResource;
class ConversationsController extends Controller
{
public function __invoke(Conversation $conversation)
    {
        return Inertia::render('conversations/Show', [
            'conversations' => ConversationResource::make($conversation),
        ]);
    }
}
