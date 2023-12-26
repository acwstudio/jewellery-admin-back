<?php

namespace Database\Factories\Modules\Delivery\Models;

use App\Modules\Delivery\Models\PVZ1C;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Delivery\Models\PVZ1C>
 */
class PVZ1CFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PVZ1C::class;


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
            'dadata_checked' => true,
        ];
    }
}
