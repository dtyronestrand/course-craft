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

    public function execute(array $data)
    {
    
        return $this->appointmentRepository->store($data);
        if(isset($data['guests'])) {
            $this->appointmentRepository->syncGuests($appointment, $data['guests']);
        }
    }
}