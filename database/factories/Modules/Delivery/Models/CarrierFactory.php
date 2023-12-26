<?php

namespace Database\Factories\Modules\Delivery\Models;

use App\Modules\Delivery\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CarrierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Carrier::class;


    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'carrier_id' => Str::random(10),
            'carrier_name' => Str::random(10),
            'is_active' => true,
            'is_banned' => false,
            'is_pvz' => false,
            'range' => rand(1, 5),
            'price' => 10,
            'fact_price' => 10,
            'delivery_time_min' => 1,
            'delivery_time_max' => 5,
            'min_weight' => 0,
            'max_weight' => 9000000,
            'min_amount' => 0,
            'max_amount' => 9000000,
            'coefficient' => 1.5,
            'term' => Str::random(5),
            'kladr' => Str::random(10),
        ];
    }
}
