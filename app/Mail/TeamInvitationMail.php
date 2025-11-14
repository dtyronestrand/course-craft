<?php

namespace App\Mail;

use App\Models\TeamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invitation;

    public function __construct(TeamInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this->subject('You\'ve been invited to join ' . $this->invitation->team->name)
            ->markdown('emails.team-invitation');
    }
}