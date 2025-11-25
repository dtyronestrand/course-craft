<?php

namespace App\Services;

use App\Models\DevelopmentCycle;
use App\Repositories\DevelopmentCycleRepository;
use App\Actions\DevelopmentCycles\CreateDevelopmentCycleAction;
use App\Actions\DevelopmentCycles\UpdateDevelopmentCycleAction;
use App\Actions\DevelopmentCycles\DeleteDevelopmentCycleAction;

class DevelopmentCycleService
{
    protected $developmentCycleRepository;

    public function __construct(DevelopmentCycleRepository $developmentCycleRepository)
    {
        $this->developmentCycleRepository = $developmentCycleRepository;
    }

    public function createDevelopmentCycle(array $data)
    {
      return (new CreateDevelopmentCycleAction($this->developmentCycleRepository))->execute($data);
    }

    public function updateDevelopmentCycle(DevelopmentCycle $developmentCycle, array $data)
    {
       return(new UpdateDevelopmentCycleAction($this->developmentCycleRepository))->execute($developmentCycle, $data);
    }

    public function deleteDevelopmentCycle(DevelopmentCycle $developmentCycle)
    {
        return (new DeleteDevelopmentCycleAction($this->developmentCycleRepository))->execute($developmentCycle);
    }
    public function updateOrCreateDevelopmentCycle($data)
    {
        return $this->developmentCycleRepository->updateOrCreate($data);
    }
    public function getDevelopmentCycleById($id)
    {
        return $this->developmentCycleRepository->findById($id);
    }

    public function getAllDevelopmentCycles()
    {
        return $this->developmentCycleRepository->getAll();
    }
}

