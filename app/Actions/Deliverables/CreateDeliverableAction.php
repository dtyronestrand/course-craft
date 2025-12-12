<?php

namespace App\Actions\Deliverables;

use App\Repositories\CourseRepository;
use App\Repositories\DeliverableRepository;
use Illuminate\Support\Facades\DB;

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
        $this.courseRepository = $courseRepository;
        $this->attachDeliverableToCourseAction = $attachDeliverableToCourseAction;
    }

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {
            $deliverable = $this->deliverableRepository->create([
                'name' => $data['name'],
                'template_days_offset' => $data['template_days_offset'],
            ]);

            $courses = $this->courseRepository->all();

            foreach ($courses as $course) {
                $this->attachDeliverableToCourseAction->execute($course, $deliverable);
            }

            return $deliverable;
        });
    }
}
