<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Collections;

use App\Modules\Collections\Enums\CollectionImageUrlTypeEnum;
use App\Modules\Collections\Models\Collection;
use App\Modules\Collections\Models\CollectionImageUrl;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollectionImageUrlFactory extends Factory
{
    protected $model = CollectionImageUrl::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'collection_id' => Collection::factory(),
            'path' => 'collections/' . $this->faker->randomDigitNotNull . '.jpg',
            'type' => $this->faker->randomElement(CollectionImageUrlTypeEnum::cases())
        ];
    }
}
