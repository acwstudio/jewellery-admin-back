<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Collections;

use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\Favorite;
use App\Modules\Collections\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;

class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'slug' => $this->faker->slug(),
            'name' => $this->faker->text(50),
            'description' => $this->faker->text(50),
            'background_color' => $this->faker->hexColor(),
            'collection_id' => Collection::factory(),
            'image_id' => File::factory(),
            'image_mob_id' => File::factory()
        ];
    }
}
