<?php

namespace App\Actions\AdminSettings;

use App\Models\AdminSetting;
use App\Repositories\AdminSettingRepository;

class DeleteAdminSettingAction
{
    protected AdminSettingRepository $adminSettingRepository;

    public function __construct(AdminSettingRepository $adminSettingRepository)
    {
        $this->adminSettingRepository = $adminSettingRepository;
    }

    public function execute(AdminSetting $adminSetting): void
    {
        $this->adminSettingRepository->delete($adminSetting);
    }
}