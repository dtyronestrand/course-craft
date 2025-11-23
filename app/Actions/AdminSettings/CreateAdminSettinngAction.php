<?php

namespace App\Actions\AdminSettings;

use App\Models\AdminSetting;
use App\Repositories\AdminSettingRepository;

class CreateAdminSettinngAction
{
    protected AdminSettingRepository $adminSettingRepository;

    public function __construct(AdminSettingRepository $adminSettingRepository)
    {
        $this->adminSettingRepository = $adminSettingRepository;
    }

    public function execute(array $data): AdminSetting
    {
        return $this->adminSettingRepository->create($data);
    }
}