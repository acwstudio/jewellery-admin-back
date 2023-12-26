<?php

declare(strict_types=1);

namespace App\Modules\Live\Services;

use App\Modules\Live\Models\Setting;
use App\Modules\Live\Repositories\SettingRepository;
use App\Modules\Live\Support\Blueprints\SettingBlueprint;
use App\Modules\Live\Support\Filters\SettingFilter;
use Illuminate\Support\Collection;

class SettingService
{
    public function __construct(
        private readonly SettingRepository $settingRepository
    ) {
    }

    public function getSetting(int $id): ?Setting
    {
        return $this->settingRepository->getById($id);
    }

    public function getSettings(SettingFilter $filter): Collection
    {
        return $this->settingRepository->getList($filter);
    }

    public function createOrUpdateSetting(SettingBlueprint $settingBlueprint): Setting
    {
        return $this->settingRepository->createOrUpdate($settingBlueprint);
    }

    public function deleteSetting(int $id): void
    {
        $setting = $this->settingRepository->getById($id, true);
        $this->settingRepository->delete($setting);
    }
}
