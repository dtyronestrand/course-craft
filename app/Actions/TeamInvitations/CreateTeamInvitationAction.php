<?php

namespace App\Actions\TeamInvitations;

use App\Mail\TeamInvitationMail;
use App\Models\Team;
use App\Repositories\TeamInvitationRepository;
use Illuminate\Support\Facades\Mail;

class CreateTeamInvitationAction
{
    protected $teamInvitationRepository;

    public function __construct(TeamInvitationRepository $teamInvitationRepository)
    {
        $this->teamInvitationRepository = $teamInvitationRepository;
    }

    public function execute(Team $team, array $data)
    {
        if ($this->teamInvitationRepository->isUserInTeam($team, $data['email'])) {
            return ['error' => 'User is already a team member'];
        }

        if ($this->teamInvitationRepository->findPendingInvitation($team, $data['email'])) {
            return ['error' => 'An invitation has already been sent to this email'];
        }

        $invitation = $this->teamInvitationRepository->create($team, $data);

        Mail::to($data['email'])->send(new TeamInvitationMail($invitation));

        return ['success' => 'Invitation sent successfully'];
    }
}
