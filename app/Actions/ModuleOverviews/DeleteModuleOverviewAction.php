<?php

namespace App\Actions\ModuleOverviews;

use App\Repositories\ModuleOverviewRepository;

class DeleteModuleOverviewAction
{
    protected $moduleOverviewRepository;

    public function __construct(ModuleOverviewRepository $moduleOverviewRepository)
    {
        $this->moduleOverviewRepository = $moduleOverviewRepository;
    }

    public function execute($moduleOverview)
    {
        return $this->moduleOverviewRepository->delete($moduleOverview);
    }
}