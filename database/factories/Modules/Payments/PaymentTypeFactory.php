<?php

namespace Database\Factories\Modules\Payments;

use App\Modules\Payments\Models\Merchant;
use App\Modules\Payments\Models\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PaymentTypeFactory extends Factory
{
    protected $model = PaymentType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->text(50),
            'enabled' => fake()->boolean(),
        ];
    }

    public function enabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => true,
            ];
        });
    }

    public function disabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => false,
            ];
        });
    }

    public function withMerchant()
    {
        return $this->state(function () {
            return [
                'merchant_id' => Merchant::query()->pluck('id')->random(),
            ];
        });
    }
}
