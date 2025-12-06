<?php

namespace App\Actions\Appointments;

use App\Repositories\AppointmentRepository;

class GetAppointmentsAction
{
    protected AppointmentRepository $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function execute($user)
    {
        $appointments = $this->appointmentRepository->getForUser($user);
        $userId = $user->id;
        if($appointments && $appointments->isNotEmpty()) {
            return $appointments->map(function ($appointment) use ($userId) {
                return [
                    'id' => 'a-' . $appointment->id,
                    'subject' => 'MEETING: ' . $appointment->subject,
                    'start_time' => $appointment->start_time->toIso8601String(),
                    'end_time' => $appointment->end_time->toIso8601String(),
                    'allDay' => $appointment->allDay ?? false,
                    'extendedProps' => [
                        'type' => 'appointment',
                        'host' => $appointment->host->id === $userId ? true : false,
                        'host_name' => $appointment->host->first_name . ' ' . $appointment->host->last_name,
                        'guests' => $appointment->guests->map(fn($guest) => $guest->first_name . ' ' . $guest->last_name)->join(', '),
                        'notes' => $appointment->notes,
                    ],
                    'color' => '#10b981',
                ];
            });
        }
        return collect([]);
    }
}