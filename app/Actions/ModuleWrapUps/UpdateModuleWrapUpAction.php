<?php

namespace App\Actions\ModuleWrapUps;

use App\Models\ModuleWrapUp;
use App\Repositories\ModuleWrapUpRepository;

class UpdateModuleWrapUpAction
{
    protected $moduleWrapUpRepository;

    public function __construct(ModuleWrapUpRepository $moduleWrapUpRepository)
    {
        $this->moduleWrapUpRepository = $moduleWrapUpRepository;
    }

    public function execute(ModuleWrapUp $moduleWrapUp, array $data)
    {
        return $this->moduleWrapUpRepository->update($moduleWrapUp, [
            'content' => $data['content'],
        ]);
    }
}
