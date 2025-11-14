<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Mail\TeamInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TeamInvitationController extends Controller
{
    public function store(Request $request, Team $team)
    {
        // Authorization check
        if (!$team->users()->where('user_id', Auth::id())->wherePivot('role', 'admin')->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:admin,member',
        ]);

        // Check if user already exists in team
        if ($team->users()->where('email', $validated['email'])->exists()) {
            return back()->withErrors(['email' => 'User is already a team member']);
        }

        // Check for existing pending invitation
        $existingInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('email', $validated['email'])
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->withErrors(['email' => 'An invitation has already been sent to this email']);
        }

        // Create invitation
        $invitation = TeamInvitation::create([
            'team_id' => $team->id,
            'email' => $validated['email'],
            'token' => Str::random(64),
            'role' => $validated['role'],
            'expires_at' => now()->addDays(7),
        ]);

        // Send email
        Mail::to($validated['email'])->send(new TeamInvitationMail($invitation));

        return back()->with('success', 'Invitation sent successfully');
    }

    public function show($token)
    {
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();

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
        $invitation = TeamInvitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired() || $invitation->isAccepted()) {
            abort(410, 'Invitation is no longer valid');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $invitation->email,
            'password' => bcrypt($validated['password']),
        ]);

        // Assign to team
        $invitation->team->users()->attach($user->id, ['role' => $invitation->role]);

        // Mark invitation as accepted
        $invitation->update(['accepted_at' => now()]);

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to the team!');
    }
}