<?php

namespace App\Observers;

use App\Models\DevelopmentCycle;
use App\Services\DevelopmentCycleService;
use App\Services\CalendarService;



class DevelopmentCycleObserver
{
    protected $developmentCycleService;
    protected $calendarService;
    public function __construct(DevelopmentCycleService $developmentCycleService, CalendarService $calendarService)
    {
        $this->developmentCycleService = $developmentCycleService;
        $this->calendarService = $calendarService;
    }
public function created(DevelopmentCycle $cycle)
{
    $dueDates = $this->developmentCycleService->getCycleDueDates($cycle->id);

    foreach ($dueDates as $date) {
        $this->calendarService->addEvent([
            'title' => 'Deliverable Due: ' . $date['deliverable_name'],
            'start' => $date['due_date'],
            'end' => $date['due_date'],
            'allDay' => true,
        ]);
    }
}
}