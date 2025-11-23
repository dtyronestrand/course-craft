<?php

namespace App\Actions\Deliverables;

use App\Models\Course;
use App\Models\Deliverable;
use App\Repositories\DeliverableRepository;

class AttachDeliverableToCourseAction
{
    protected $deliverableRepository;

    public function __construct(DeliverableRepository $deliverableRepository)
    {
        $this->deliverableRepository = $deliverableRepository;
    }

    public function execute(Course $course, Deliverable $deliverable, array $pivotData = [])
    {
        return $this->deliverableRepository->attachToCourse($course, $deliverable, $pivotData);
    }
}
