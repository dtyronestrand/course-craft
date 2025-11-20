<?php

namespace App\Actions\TeamInvitations;

use App\Models\TeamInvitation;
use App\Models\User;
use App\Repositories\TeamInvitationRepository;

class AcceptTeamInvitationAction
{
    protected $teamInvitationRepository;

    public function __construct(TeamInvitationRepository $teamInvitationRepository)
    {
        $this->teamInvitationRepository = $teamInvitationRepository;
    }

    public function execute(TeamInvitation $invitation, array $data): User
    {
        return $this->teamInvitationRepository->createUserAndAttachToTeam($invitation, $data);
    }
}
