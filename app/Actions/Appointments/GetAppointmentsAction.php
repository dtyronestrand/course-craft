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
        if($appointments && $appointments->isNotEmpty()) {
            return $appointments->map(function ($appointment){
                return [
                    'id' => 'a-' . $appointment->id,
                    'title' => 'MEETING: ' . $appointment->subject,
                    'start' => $appointment->start_time->toIso8601String(),
                    'end' => $appointment->end_time->toIso8601String(),
                    'allDay' => $appointment->allDay ?? false,
                    'extendedProps' => [
                        'type' => 'appointment',
                        'host' => $appointment->host->name,
                        'guests' => $appointment->guests->pluck('name')->toArray(),
                        'notes' => $appointment->notes,
                    ],
                    'color' => '#10b981',
                ];
            });
        }
        return collect([]);
    }
}