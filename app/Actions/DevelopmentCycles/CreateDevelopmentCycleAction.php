<?php

namespace App\Actions\DevelopmentCycles;


use App\Repositories\DevelopmentCycleRepository;

class CreateDevelopmentCycleAction
{
    protected $developmentCycleRepository;

    public function __construct(DevelopmentCycleRepository $developmentCycleRepository)
    {
        $this->developmentCycleRepository = $developmentCycleRepository;
    }

    public function execute(array $data)
    {
        return $this->developmentCycleRepository->create([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            
        ]);
    }
}