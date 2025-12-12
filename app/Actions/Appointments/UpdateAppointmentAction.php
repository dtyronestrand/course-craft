<?php

namespace App\Actions\Appointments;

use App\Models\Appointment;
use App\Repositories\AppointmentRepository;

class UpdateAppointmentAction
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function execute(Appointment $appointment, array $data)
    {
        $this->appointmentRepository->update($appointment, [
            'subject' => $data['subject'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'notes' => $data['notes'] ?? null,
        ]);
        if (isset($data['guests'])) {
            $this->appointmentRepository->syncGuests($appointment, $data['guests']);
        }

        return $appointment;
    }
}
