<?php

declare(strict_types=1);

namespace App\Modules\Live\Repositories;

use App\Modules\Live\Enums\SettingNameEnum;
use App\Modules\Live\Models\Setting;
use App\Modules\Live\Support\Blueprints\SettingBlueprint;
use App\Modules\Live\Support\Filters\SettingFilter;
use App\Packages\Support\FilterQuery\FilterQueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SettingRepository
{
    public function getById(int $id, bool $fail = false): ?Setting
    {
        if ($fail) {
            return Setting::findOrFail($id);
        }

        return Setting::find($id);
    }

    /**
     * @param SettingFilter $filter
     * @param bool $fail
     * @return Collection<Setting>
     */
    public function getList(SettingFilter $filter, bool $fail = false): Collection
    {
        $query = FilterQueryBuilder::fromQuery(Setting::query())->withFilter($filter)->create();

        /** @var Collection<Setting> $models */
        $models = $query->get();

        if ($fail && $models->count() === 0) {
            throw new ModelNotFoundException();
        }

        return $models;
    }

    public function create(SettingBlueprint $settingBlueprintData): Setting
    {
        $setting = new Setting([
            'name' => $settingBlueprintData->name,
            'value' => $settingBlueprintData->value
        ]);

        $setting->save();

        return $setting;
    }

    public function update(Setting $setting, ?string $value): void
    {
        $setting->update([
            'value' => $value
        ]);
    }

    public function delete(Setting $setting): void
    {
        $setting->delete();
    }

    public function createOrUpdate(SettingBlueprint $settingBlueprintData): Setting
    {
        $setting = $this->getList(new SettingFilter(name: $settingBlueprintData->name->value))->first();

        if ($setting instanceof Setting) {
            $this->update($setting, $settingBlueprintData->value);
            return $setting->refresh();
        }

        return $this->create($settingBlueprintData);
    }
}
