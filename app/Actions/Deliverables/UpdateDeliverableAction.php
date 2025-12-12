<?php

namespace App\Actions\Deliverables;

use App\Models\Deliverable;
use App\Repositories\DeliverableRepository;


class UpdateDeliverableAction
{
    protected $deliverableRepository;

    public function __construct(DeliverableRepository $deliverableRepository)
    {
        $this->deliverableRepository = $deliverableRepository;
    }

    public function execute(Deliverable $deliverable, array $data)
    {
        
        return $this->deliverableRepository->update($deliverable, $data);
    }
}
