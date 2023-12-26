<?php

namespace Database\Factories\Modules\Delivery\Models;

use App\Modules\Delivery\Models\IpLogOperatorsInner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<IpLogOperatorsInner>
 */
class IpLogOperatorsInnerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IpLogOperatorsInner::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'group' => 1,
            'type' => "test",
            'courier_1c' => Str::random(10),
            'courier_name' => Str::random(10),
            'is_active' => true,
        ];
    }
}
