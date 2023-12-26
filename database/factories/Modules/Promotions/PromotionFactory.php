<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'external_id' => $this->faker->uuid(),
            'is_active' => true
        ];
    }
}
