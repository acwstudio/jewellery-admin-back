<?php

namespace Database\Factories\Modules\Store;

use App\Modules\Stores\Models\StoreType;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreTypeFactory extends Factory
{
    protected $model = StoreType::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name
        ];
    }
}
