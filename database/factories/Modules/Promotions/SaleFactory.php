<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'promotion_id' => Promotion::factory(),
            'title' => $this->faker->title,
            'slug' => $this->faker->unique()->slug(1)
        ];
    }
}
