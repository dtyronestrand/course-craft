<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
protected $fillable =['type', 'subject'];

public function messages()
{
    return $this->hasMany(Message::class);  
}
public function participants()
{
    return $this->belongsToMany(User::class, 'participants');
}
public function participantsInConversation()
{
    return $this->participants()->where('user_id', '!=', Auth::id())->get();
}
}