<?php

namespace App\Actions\Deliverables;

use App\Repositories\CourseRepository;
use App\Repositories\DeliverableRepository;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateDeliverableAction
{
    protected $deliverableRepository;
    protected $courseRepository;
    protected $attachDeliverableToCourseAction;

    public function __construct(
        DeliverableRepository $deliverableRepository,
        CourseRepository $courseRepository,
        AttachDeliverableToCourseAction $attachDeliverableToCourseAction
    ) {
        $this->deliverableRepository = $deliverableRepository;
        $this->courseRepository = $courseRepository;
        $this->attachDeliverableToCourseAction = $attachDeliverableToCourseAction;
    }

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            $deliverable = $this->deliverableRepository->create([
                'name' => $data['name'],
                'template_days_offset' => $data['template_days_offset'],
            ]);

            $courses = $this->courseRepository->getAllWithRelations();

            foreach ($courses as $course) {
                $pivotData = [
                    'due_date' => $course->developmentCycle && $course->developmentCycle->start_date
                        ? Carbon::parse($course->developmentCycle->start_date)->addDays($deliverable->template_days_offset)
                        : null,
                    'is_done' => false,
                    'missed_due_date_count' => 0,
                ];
                $this->attachDeliverableToCourseAction->execute($course, $deliverable, $pivotData);
            }

            return $deliverable;
        });
    }
}
