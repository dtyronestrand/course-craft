<?php

namespace App\Services;

use App\Actions\ModuleWrapUps\CreateModuleWrapUpAction;
use App\Actions\ModuleWrapUps\UpdateModuleWrapUpAction;
use App\Models\ModuleWrapUp;
use App\Repositories\ModuleWrapUpRepository;

class ModuleWrapUpService
{
    protected $moduleWrapUpRepository;

    public function __construct(ModuleWrapUpRepository $moduleWrapUpRepository)
    {
        $this->moduleWrapUpRepository = $moduleWrapUpRepository;
    }

    public function createWrapUp(array $data)
    {
        return (new CreateModuleWrapUpAction($this->moduleWrapUpRepository))->execute($data);
    }

    public function updateWrapUp(ModuleWrapUp $moduleWrapUp, array $data)
    {
        return (new UpdateModuleWrapUpAction($this->moduleWrapUpRepository))->execute($moduleWrapUp, $data);
    }
}
