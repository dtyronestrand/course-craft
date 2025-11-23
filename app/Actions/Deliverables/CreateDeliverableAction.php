<?php

namespace App\Actions\Deliverables;

use App\Repositories\DeliverableRepository;

class CreateDeliverableAction
{
    protected $deliverableRepository;

    public function __construct(DeliverableRepository $deliverableRepository)
    {
        $this->deliverableRepository = $deliverableRepository;
    }

    public function execute(array $data)
    {
       return $this->deliverableRepository->create([
        'name' => $data['name'],
        'template_days_offset' => $data['template_days_offset'],
       ]);
    }
}