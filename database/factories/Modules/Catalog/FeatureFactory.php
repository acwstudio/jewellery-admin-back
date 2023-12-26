<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Catalog;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FeatureFactory extends Factory
{
    protected $model = Feature::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(FeatureTypeEnum::cases())->value,
            'value' => $this->faker->unique()->text(10),
            'slug' => Str::slug($this->faker->slug(), '_')
        ];
    }
}
