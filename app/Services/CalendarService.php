<?php
namespace App\Services;

use App\Repositories\DeliverableRepository;
use App\Repositories\DevelopmentCycleRepository;
use App\Repositories\UserRepository;
use App\Repositories\CourseRepository;
use App\Repositories\AppointmentRepository;
use App\Actions\DevelopmentCycles\CycleDueDatesAction;
use App\Actions\Appointments\GetAppointmentsAction;


class CalendarService
{
    protected DeliverableRepository $deliverableRepo;
    protected DevelopmentCycleRepository $devCycleRepo;
    protected UserRepository $userRepo;
    protected CourseRepository $courseRepo;
    protected AppointmentRepository $appointmentRepo;

    public function __construct(
        DeliverableRepository $deliverableRepo,
        DevelopmentCycleRepository $devCycleRepo,
        UserRepository $userRepo,
        CourseRepository $courseRepo,
        AppointmentRepository $appointmentRepo
    ) {
        $this->deliverableRepo = $deliverableRepo;
        $this->devCycleRepo = $devCycleRepo;
        $this->userRepo = $userRepo;
        $this->courseRepo = $courseRepo;
        $this->appointmentRepo = $appointmentRepo;
    }
    public function addEvent(array $eventData) {
      $this->appointmentRepo->store($eventData);
    }
    public function getCycleDueDates(int $cycleId) {
        return (new CycleDueDatesAction($this->devCycleRepo))->execute($cycleId);
    }


    // Add calendar-related methods here
}