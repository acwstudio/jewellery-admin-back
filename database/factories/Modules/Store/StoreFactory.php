<?php

namespace Database\Factories\Modules\Store;

use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Models\StoreWorkTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreFactory extends Factory
{
    protected $model = Store::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'description' => fake()->text,
            'address' => fake()->address,
            'latitude' => fake()->latitude,
            'longitude' => fake()->longitude,
            'phone' => fake()->numberBetween(70000000000, 80000000000),
        ];
    }

    public function withWorkTime(int $count = 5): self
    {
        return $this->afterCreating(
            function (Store $store) use ($count) {
                StoreWorkTime::factory($count)->withStoreId($store->id)->create();
            }
        );
    }
}
