<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Support\Str;

class TeamInvitationRepository
{
    public function isUserInTeam(Team $team, string $email): bool
    {
        return $team->users()->where('email', $email)->exists();
    }

    public function findPendingInvitation(Team $team, string $email): ?TeamInvitation
    {
        return TeamInvitation::where('team_id', $team->id)
            ->where('email', $email)
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();
    }

    public function create(Team $team, array $data): TeamInvitation
    {
        return TeamInvitation::create([
            'team_id' => $team->id,
            'email' => $data['email'],
            'role' => $data['role'],
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7),
        ]);
    }

    public function findByToken(string $token): ?TeamInvitation
    {
        return TeamInvitation::where('token', $token)->firstOrFail();
    }

    public function createUserAndAttachToTeam(TeamInvitation $invitation, array $userData): User
    {
        $user = User::create([
            'name' => $userData['name'],
            'email' => $invitation->email,
            'password' => bcrypt($userData['password']),
        ]);

        $invitation->team->users()->attach($user->id, ['role' => $invitation->role]);
        $invitation->update(['accepted_at' => now()]);

        return $user;
    }
}
