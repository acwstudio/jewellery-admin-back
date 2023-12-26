<?php

namespace Database\Factories\Modules\Store;

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use App\Modules\Stores\Models\StoreWorkTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoreWorkTimeFactory extends Factory
{
    protected $model = StoreWorkTime::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'day' => StoreWorkDayEnum::MONDAY,
            'start_time' => fake()->time,
            'end_time' => fake()->time
        ];
    }

    public function withStoreId(int $storeId): self
    {
        return $this->state(function () use ($storeId) {
            return ['store_id' => $storeId];
        });
    }
}
