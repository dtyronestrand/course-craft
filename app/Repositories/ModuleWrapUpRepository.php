<?php

namespace App\Repositories;

use App\Models\ModuleWrapUp;
use App\Models\ModuleItem;

class ModuleWrapUpRepository
{
    public function create(array $data)
    {
        return ModuleWrapUp::create($data);
    }

    public function createModuleItem(array $data)
    {
        return ModuleItem::create($data);
    }
    public function getMaxOrderIndex(int $moduleId)
    {
        return ModuleItem::where('course_module_id', $moduleId)->max('order_index') ?? -1;
    }

    public function update(ModuleWrapUp $moduleWrapUp, array $data)
    {
        return $moduleWrapUp->update($data);
    }
}
