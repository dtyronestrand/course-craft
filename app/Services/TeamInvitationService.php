<?php

namespace App\Services;

use App\Actions\TeamInvitations\AcceptTeamInvitationAction;
use App\Actions\TeamInvitations\CreateTeamInvitationAction;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Repositories\TeamInvitationRepository;
use Illuminate\Support\Facades\Auth;

class TeamInvitationService
{
    protected $teamInvitationRepository;

    public function __construct(TeamInvitationRepository $teamInvitationRepository)
    {
        $this->teamInvitationRepository = $teamInvitationRepository;
    }

    public function createInvitation(Team $team, array $data)
    {
        return (new CreateTeamInvitationAction($this->teamInvitationRepository))->execute($team, $data);
    }

    public function findInvitationByToken(string $token): ?TeamInvitation
    {
        return $this->teamInvitationRepository->findByToken($token);
    }

    public function acceptInvitation(string $token, array $data)
    {
        $invitation = $this->teamInvitationRepository->findByToken($token);

        if ($invitation->isExpired() || $invitation->isAccepted()) {
            return false;
        }

        $user = (new AcceptTeamInvitationAction($this->teamInvitationRepository))->execute($invitation, $data);

        Auth::login($user);

        return true;
    }
}
