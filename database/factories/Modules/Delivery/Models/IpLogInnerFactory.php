<?php

namespace Database\Factories\Modules\Delivery\Models;

use App\Modules\Delivery\Models\IpLogInner;
use App\Modules\Delivery\Policies\DeliveryPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Delivery\Models\IpLogInner>
 */
class IpLogInnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IpLogInner::class;



    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country' => $this->faker->country(),
            'region' => Str::random(10),
            'type' => Str::random(10),
            'price_for_client' => $this->faker->randomNumber(2),
            'markup_percent' => $this->faker->randomNumber(2),
            'courier_group' => $this->faker->randomNumber(2),
            'settlement' => $this->faker->city(),
            'costs' => $this->faker->randomFloat(2),
            'deliveries' => $this->faker->randomNumber(2),
            'is_active' => true,
            'avg_cost' => $this->faker->randomFloat(2),
            'avg_margin_cost' => $this->faker->randomFloat(2),
            'markup' => $this->faker->randomFloat(2),
            'zone' => strtoupper(Str::random(1)),
            'min' => rand(0,10),
            'max' => rand(0,10),
            'month_year_of_delivery' => '2022-02',
            'delivery_time' => DeliveryPolicy::DEFAULT_DELIVERY_TIME
        ];
    }
}
