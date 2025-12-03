<?php

namespace App\Repositories;

use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class MesageRepository {
    public function getUserThreads($userId)
    {

        return Thread::forUser(Auth::id())->latest('updated_at')->get();
    }

    public function getThreadMessages($threadId)
    {
       
       return  $thread = Thread::findOrFail($threadId);
    
    }
    
}