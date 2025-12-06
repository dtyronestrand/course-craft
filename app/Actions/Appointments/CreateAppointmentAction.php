<?php

namespace App\Actions\Appointments;

use App\Repositories\AppointmentRepository;


class CreateAppointmentAction
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function execute( $data)
    {
         $validated = $data->validate([
            'user' => 'required',
            'subject' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'notes' => 'nullable|string',
            'guests' => 'nullable|array',
            'guests.*' => 'exists:users,id',
        ]);
        $appointment = $this->appointmentRepository->store($validated);

        if(isset($validated['guests'])) {
            $this->appointmentRepository->syncGuests($appointment, $validated['guests']);
        }
        
        return $appointment;
    }
}