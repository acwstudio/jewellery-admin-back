<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Collections;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionFactory extends Factory
{
    protected $model = Collection::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug(),
            'name' => $this->faker->text(50),
            'description' => $this->faker->text(50),
            'preview_image_id' => File::factory(),
            'preview_image_mob_id' => File::factory(),
            'banner_image_id' => File::factory(),
            'banner_image_mob_id' => File::factory(),
            'is_active' => true
        ];
    }
}
