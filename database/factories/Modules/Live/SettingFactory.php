<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Live;

use App\Modules\Live\Enums\SettingNameEnum;
use App\Modules\Live\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'name' => fake()->unique()->randomElement(SettingNameEnum::cases())->value,
        ];
    }
}
