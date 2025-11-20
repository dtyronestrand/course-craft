<?php

namespace App\Repositories;

use App\Models\ModuleOverview;
use App\Models\ModuleItem;

class ModuleOverviewRepository
{
    public function create(array $data)
    {
        return ModuleOverview::create($data);
    }

    public function createModuleItem(array $data)
    {
        return ModuleItem::create($data);
    }
    public function getMaxOrderIndex(int $moduleId)
    {
        return ModuleItem::where('course_module_id', $moduleId)->max('order_index') ?? -1;
    }

    public function update(ModuleOverview $moduleOverview, array $data)
    {
        return $moduleOverview->update($data);
    }

    public function delete(ModuleOverview $moduleOverview)
    {
        return $moduleOverview->delete();
    }
}
