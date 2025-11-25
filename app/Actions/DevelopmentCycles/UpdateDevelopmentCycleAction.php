<?php

namespace App\Actions\DevelopmentCycles;

use App\Repositories\DevelopmentCycleRepository;

class UpdateDevelopmentCycleAction
{
    protected $developmentCycleRepository;

    public function __construct(DevelopmentCycleRepository $developmentCycleRepository)
    {
        $this->developmentCycleRepository = $developmentCycleRepository;
    }

    public function execute($developmentCycleId, array $data)
    {
        $developmentCycle = $this->developmentCycleRepository->findById($developmentCycleId);

        if (!$developmentCycle) {
            throw new \Exception("Development Cycle not found.");
        }

        $this->developmentCycleRepository->update($developmentCycle, [
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
        ]);

        return $developmentCycle;
    }
}