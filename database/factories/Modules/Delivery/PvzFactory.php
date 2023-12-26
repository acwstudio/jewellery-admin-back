<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Delivery;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\Pvz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class PvzFactory extends Factory
{
    protected $model = Pvz::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'external_id' => $this->faker->uuid,
            'carrier_id' => Carrier::factory(),
            'latitude' => (string)$this->faker->latitude,
            'longitude' => (string)$this->faker->longitude,
            'work_time' => '09:00-21:00',
            'area' => 'Москва',
            'city' => 'Москва',
            'district' => $this->faker->randomElement([
                'Москва',
                'Свиблово',
                'Южное Бутово',
                'Выхино-Жулебино',
                'Отрадное',
            ]),

            'street' => $this->faker->streetName,
            'address' => $this->faker->address,
            'price' => Money::RUB($this->faker->numberBetween(100, 3000) * 100)
        ];
    }
}
