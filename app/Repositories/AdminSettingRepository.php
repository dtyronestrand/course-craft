<?php

namespace App\Repositories;

use App\Models\AdminSetting;

class AdminSettingRepository
{
    public function getAll()
    {
        return AdminSetting::all();
    }
    public function create(array $data)
    {
        return AdminSetting::create($data);
    }
    public function update(AdminSetting $adminSetting, array $data)
    {
        $adminSetting->update($data);
        return $adminSetting;
    }
    public function delete(AdminSetting $adminSetting)
    {
        $adminSetting->delete();
    }
}