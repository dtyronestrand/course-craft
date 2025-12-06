<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentInvtation extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(public $appointment)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => 'You have been invited to an appointment.',
            'appointment_id' => $this->appointment->id,
            'subject' => $this->appointment->subject,
            'start_time' => $this->appointment->start_time,
            'end_time' => $this->appointment->end_time,
            'host_name' => $this->appointment->host->first_name . ' ' . $this->appointment->host->last_name,
            'accepted' => false,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'You have been invited to an appointment.',
            'appointment_id' => $this->appointment->id,
            'subject' => $this->appointment->subject,
            'start_time' => $this->appointment->start_time,
            'end_time' => $this->appointment->end_time,
            'host_name' => $this->appointment->host->first_name . ' ' . $this->appointment->host->last_name,
            'accepted' => false,
        ];
    }
}
