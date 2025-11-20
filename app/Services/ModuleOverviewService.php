<?php

namespace App\Services;

use App\Actions\ModuleOverviews\CreateModuleOverviewAction;
use App\Actions\ModuleOverviews\UpdateModuleOverviewAction;
use App\Models\ModuleOverview;
use App\Repositories\ModuleOverviewRepository;

class ModuleOverviewService
{
    protected $moduleOverviewRepository;

    public function __construct(ModuleOverviewRepository $moduleOverviewRepository)
    {
        $this->moduleOverviewRepository = $moduleOverviewRepository;
    }

    public function createOverview(array $data)
    {
        return (new CreateModuleOverviewAction($this->moduleOverviewRepository))->execute($data);
    }

    public function updateOverview(ModuleOverview $moduleOverview, array $data)
    {
        return (new UpdateModuleOverviewAction($this->moduleOverviewRepository))->execute($moduleOverview, $data);
    }
}
