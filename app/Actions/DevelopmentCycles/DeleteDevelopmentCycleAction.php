<?php

namespace App\Actions\DevelopmentCycles;

use App\Repositories\DevelopmentCycleRepository;

class DeleteDevelopmentCycleAction
{
    protected $developmentCycleRepository;

    public function __construct(DevelopmentCycleRepository $developmentCycleRepository)
    {
        $this->developmentCycleRepository = $developmentCycleRepository;
    }

    public function execute($developmentCycleId)
    {
        $developmentCycle = $this->developmentCycleRepository->findById($developmentCycleId);

        if (!$developmentCycle) {
            throw new \Exception("Development Cycle not found.");
        }

        $this->developmentCycleRepository->delete($developmentCycle);

        return true;
    }
}