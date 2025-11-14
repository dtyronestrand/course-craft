<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
   protected $fillable = ['team_id', 'email', 'token', 'role', 'expires_at', 'accepted_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isAccepted()
    {
        return !is_null($this->accepted_at);
    }
}
