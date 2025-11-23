<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Deliverable;
use App\Repositories\DeliverableRepository;
use App\Actions\Deliverables\CreateDeliverableAction;
use App\Actions\Deliverables\UpdateDeliverableAction;
use App\Actions\Deliverables\DeleteDeliverableAction;
use App\Actions\Deliverables\AttachDeliverableToCourseAction;
use App\Actions\Deliverables\UpdateCourseDeliverableAction;

class DeliverableService
{
    protected $deliverableRepository;

    public function __construct(DeliverableRepository $deliverableRepository)
    {
        $this->deliverableRepository = $deliverableRepository;
    }

    public function createDeliverable(array $data)
    {
        return (new CreateDeliverableAction($this->deliverableRepository))->execute($data);
    }

    public function updateDeliverable(Deliverable $deliverable, array $data)
    {
        return (new UpdateDeliverableAction($this->deliverableRepository))->execute($deliverable, $data);
    }

    public function deleteDeliverable(Deliverable $deliverable)
    {
        return (new DeleteDeliverableAction($this->deliverableRepository))->execute($deliverable);
    }

    public function attachToCourse(Course $course, Deliverable $deliverable, array $pivotData = [])
    {
        return (new AttachDeliverableToCourseAction($this->deliverableRepository))->execute($course, $deliverable, $pivotData);
    }

    public function updateCourseDeliverable(Course $course, Deliverable $deliverable, array $pivotData)
    {
        return (new UpdateCourseDeliverableAction($this->deliverableRepository))->execute($course, $deliverable, $pivotData);
    }

    public function detachFromCourse(Course $course, Deliverable $deliverable)
    {
        return $this->deliverableRepository->detachFromCourse($course, $deliverable);
    }
}