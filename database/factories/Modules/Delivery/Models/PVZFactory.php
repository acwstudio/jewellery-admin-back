<?php

namespace Database\Factories\Modules\Delivery\Models;

use App\Modules\Delivery\Models\PVZ;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Delivery\Models\PVZ>
 */
class PVZFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PVZ::class;


    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => Str::random(10),
            'marked' => false,
            'description' => Str::random(10),
            'subdivision' => Str::random(10),
            'county' => $this->faker->city(),
            'mode_of_operation' => $this->faker->time(),
            'address' => $this->faker->address(),
            'country' => $this->faker->country(),
            'region' => $this->faker->city(),
            'phones' => Str::random(10),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'without_a_pass' => true,
            'stroller' => true,
            'not_more_15_min' => true,
            'adm_area' => Str::random(10),
            'marks' => Str::random(10),
            'dressing_rooms' => true,
            'directions' => Str::random(100),
            'payment_on_receipt' => true,
            'terminal' => true,
            'metro' => Str::random(10),
            'railway' => Str::random(10),
            'area' => Str::random(10),
            'code' => Str::random(10),
            'max_amount' => rand(0,10000),
            'delivery' => Str::random(10),
            'city' => $this->faker->city(),
            'cladr' => (string)rand(1000000000,9999999999),
            'index' => rand(100000,999999),
            'error' => null,
            'dadata_checked' => true,
            'dadata_json' => null,
            'region_without_type' => $this->faker->city(),
        ];
    }
}
