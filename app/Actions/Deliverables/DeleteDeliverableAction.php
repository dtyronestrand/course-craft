<?php

namespace App\Actions\Deliverables;

use App\Models\Deliverable;
use App\Repositories\DeliverableRepository;

class DeleteDeliverableAction
{
    protected $deliverableRepository;

    public function __construct(DeliverableRepository $deliverableRepository)
    {
        $this->deliverableRepository = $deliverableRepository;
    }

    public function execute(Deliverable $deliverable)
    {
        return $this->deliverableRepository->delete($deliverable);
    }
}
