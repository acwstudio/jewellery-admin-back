<?php

namespace Database\Factories\Modules\Payments;

use App\Modules\Payments\Models\GiftCardCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GiftCardCodeFactory extends Factory
{
    protected $model = GiftCardCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => fake()->text(6),
            'valid_till' => fake()->dateTimeBetween('+1 day', '+1 year'),
        ];
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'valid_till' => fake()->dateTimeBetween('-1 year', '-1 day')
            ];
        });
    }
}
