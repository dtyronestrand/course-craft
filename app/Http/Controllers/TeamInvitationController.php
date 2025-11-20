<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\TeamInvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TeamInvitationController extends Controller
{
    protected $teamInvitationService;

    public function __construct(TeamInvitationService $teamInvitationService)
    {
        $this->teamInvitationService = $teamInvitationService;
    }

    public function store(Request $request, Team $team)
    {
        if (!$team->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,member',
        ]);

        $result = $this->teamInvitationService->createInvitation($team, $validated);

        if (isset($result['error'])) {
            return back()->withErrors(['email' => $result['error']]);
        }

        return back()->with('success', $result['success']);
    }

    public function show($token)
    {
        $invitation = $this->teamInvitationService->findInvitationByToken($token);

        if ($invitation->isExpired()) {
            return Inertia::render('Invitations/Expired');
        }

        if ($invitation->isAccepted()) {
            return Inertia::render('Invitations/AlreadyAccepted');
        }

        return Inertia::render('Invitations/Accept', [
            'invitation' => [
                'token' => $invitation->token,
                'email' => $invitation->email,
                'team_name' => $invitation->team->name,
                'role' => $invitation->role,
            ]
        ]);
    }

    public function accept(Request $request, $token)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!$this->teamInvitationService->acceptInvitation($token, $validated)) {
            abort(410, 'Invitation is no longer valid');
        }

        return redirect()->route('dashboard')->with('success', 'Welcome to the team!');
    }
}
