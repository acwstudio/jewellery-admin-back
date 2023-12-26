<?php

namespace Database\Factories\Modules\Delivery\Models;

use App\Modules\Delivery\Models\IpLogOperators;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Delivery\Models\IpLogOperators>
 */
class IpLogOperatorsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IpLogOperators::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'group' => Str::random(10),
            'type' => Str::random(10),
            'courier_1c' => Str::random(10),
            'courier_name' => Str::random(10),
            'active' => rand(0,10) > 5 ? 1 : 0,
        ];
    }
}
