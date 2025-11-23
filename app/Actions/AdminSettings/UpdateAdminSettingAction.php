<?php

namespace App\Actions\AdminSettings;

use App\Models\AdminSetting;
use App\Repositories\AdminSettingRepository;

class UpdateAdminSettingAction
{
    protected AdminSettingRepository $adminSettingRepository;

    public function __construct(AdminSettingRepository $adminSettingRepository)
    {
        $this->adminSettingRepository = $adminSettingRepository;
    }

    public function execute(AdminSetting $adminSetting, array $data): AdminSetting
    {
        return $this->adminSettingRepository->update($adminSetting, $data);
    }
}