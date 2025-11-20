<?php

namespace App\Actions\ModuleOverviews;

use App\Repositories\ModuleOverviewRepository;

class CreateModuleOverviewAction
{
    protected $moduleOverviewRepository;

    public function __construct(ModuleOverviewRepository $moduleOverviewRepository)
    {
        $this->moduleOverviewRepository = $moduleOverviewRepository;
    }

    public function execute(array $data)
    {
        $moduleOverview = $this->moduleOverviewRepository->create([
            'content' => $data['content'],
        ]);

        $maxOrderIndex = $this->moduleOverviewRepository->getMaxOrderIndex($data['module']);

        $this->moduleOverviewRepository->createModuleItem([
            'course_module_id' => $data['module'],
            'itemable_id' => $moduleOverview->id,
            'itemable_type' => 'overview',
            'order_index' => $maxOrderIndex + 1,
        ]);

        return $moduleOverview;
    }
}
