<?php

namespace App\Actions\ModuleWrapUps;

use App\Repositories\ModuleWrapUpRepository;

class CreateModuleWrapUpAction
{
    protected $moduleWrapUpRepository;

    public function __construct(ModuleWrapUpRepository $moduleWrapUpRepository)
    {
        $this->moduleWrapUpRepository = $moduleWrapUpRepository;
    }

    public function execute(array $data)
    {
        $moduleWrapUp = $this->moduleWrapUpRepository->create([
            'content' => $data['content'],
        ]);

        $maxOrderIndex = $this->moduleWrapUpRepository->getMaxOrderIndex($data['module']);

        $this->moduleWrapUpRepository->createModuleItem([
            'course_module_id' => $data['module'],
            'itemable_id' => $moduleWrapUp->id,
            'itemable_type' => 'wrap_up',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return $moduleWrapUp;
    }
}
