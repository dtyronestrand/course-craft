<?php

namespace App\Services;

use App\Models\AdminSetting;
use App\Repositories\AdminSettingRepository;
use App\Actions\AdminSettings\CreateAdminSettinngAction;
use App\Actions\AdminSettings\UpdateAdminSettingAction;
use App\Actions\AdminSettings\DeleteAdminSettingAction;

class AdminSettingService
{
    protected AdminSettingRepository $adminSettingRepository;
    protected CreateAdminSettinngAction $createAdminSettinngAction;
    protected UpdateAdminSettingAction $updateAdminSettingAction;
    protected DeleteAdminSettingAction $deleteAdminSettingAction;

    public function __construct(
        AdminSettingRepository $adminSettingRepository,
        CreateAdminSettinngAction $createAdminSettinngAction,
        UpdateAdminSettingAction $updateAdminSettingAction,
        DeleteAdminSettingAction $deleteAdminSettingAction
    ) {
        $this->adminSettingRepository = $adminSettingRepository;
        $this->createAdminSettinngAction = $createAdminSettinngAction;
        $this->updateAdminSettingAction = $updateAdminSettingAction;
        $this->deleteAdminSettingAction = $deleteAdminSettingAction;
    }

    public function getAllSettings()
    {
        return $this->adminSettingRepository->getAll();
    }

    public function createSetting(array $data): AdminSetting
    {
        return $this->createAdminSettinngAction->execute($data);
    }

    public function updateSetting(AdminSetting $adminSetting, array $data): AdminSetting
    {
        return $this->updateAdminSettingAction->execute($adminSetting, $data);
    }

    public function deleteSetting(AdminSetting $adminSetting): void
    {
        $this->deleteAdminSettingAction->execute($adminSetting);
    }
}