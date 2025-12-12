<?php

namespace App\Services;
use App\Actions\Appointments\CreateAppointmentAction;
use App\Actions\Appointments\GetAppointmentsAction;
use App\Actions\Appointments\UpdateAppointmentAction;
use App\Repositories\AppointmentRepository;
use Illuminate\Http\Request;

class AppointmentService
{
    protected AppointmentRepository $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function createAppointment(Request $request)
    {
        $createAppointmentAction = new CreateAppointmentAction($this->appointmentRepository);
        return $createAppointmentAction->execute($request);
    }
        public function getUserAppointments($user) {
        return (new GetAppointmentsAction($this->appointmentRepository))->execute($user);
    }
    public function updateAppointment($appointment, array $data) {
        $updateAppointmentAction = new UpdateAppointmentAction($this->appointmentRepository);
        return $updateAppointmentAction->execute($appointment, $data);
    }
}
