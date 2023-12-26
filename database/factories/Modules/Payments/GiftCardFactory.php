<?php

namespace Database\Factories\Modules\Payments;

use App\Modules\Payments\Models\GiftCard;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GiftCardFactory extends Factory
{
    protected $model = GiftCard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'cycle' => fake()->numerify('000#'),
            'number' => fake()->numerify('01000#########'),
            'nominal' => function (array $attributes) {
                return Money::RUB(fake()->randomElement([100000, 200000, 500000]));
            },
            'is_activated' => fake()->boolean,
            'activated_at' => function (array $attributes) {
                if ($attributes['is_activated'] === true) {
                    return fake()->dateTime;
                }

                return null;
            },
            'sku' => fake()->text('10'),
            'order_id' => fake()->randomNumber(6),
        ];
    }

    public function nonActivated(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_activated' => false,
                'activated_at' => null
            ];
        });
    }

    public function activated(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_activated' => true,
                'activated_at' => fake()->dateTime
            ];
        });
    }

    public function withOrderId(int $orderId): self
    {
        return $this->state(function () use ($orderId) {
            return [
                'order_id' => $orderId
            ];
        });
    }
}
