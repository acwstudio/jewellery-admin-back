<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class TmpCheckoutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'is_active' => true,
            'token' => $this->faker->unique()->creditCardNumber,
            'data' => json_encode(['test_property' => 'test_value']),
        ];
    }
}
