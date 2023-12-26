<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Promotions;

use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromocodeUsageFactory extends Factory
{
    protected $model = PromocodeUsage::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'shop_cart_token' => $this->faker->uuid(),
            'user_id' => User::factory(),
            'is_active' => true
        ];
    }
}
