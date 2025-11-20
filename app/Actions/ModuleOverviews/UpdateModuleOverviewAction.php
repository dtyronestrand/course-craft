<?php

namespace App\Actions\ModuleOverviews;

use App\Models\ModuleOverview;
use App\Repositories\ModuleOverviewRepository;

class UpdateModuleOverviewAction
{
    protected $moduleOverviewRepository;

    public function __construct(ModuleOverviewRepository $moduleOverviewRepository)
    {
        $this->moduleOverviewRepository = $moduleOverviewRepository;
    }

    public function execute(ModuleOverview $moduleOverview, array $data)
    {
        return $this->moduleOverviewRepository->update($moduleOverview, [
            'content' => $data['content'],
        ]);
    }
}
