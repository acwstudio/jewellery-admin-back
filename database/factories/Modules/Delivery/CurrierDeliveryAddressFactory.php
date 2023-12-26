<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Delivery;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrierDeliveryAddressFactory extends Factory
{
    protected $model = CurrierDeliveryAddress::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'address' => $this->faker->address(),
            'zip_code' => 111222,
            'region' => $this->faker->country(),
            'city' => $this->faker->city(),
            'street' => $this->faker->streetName(),
            'house' => $this->faker->buildingNumber(),
            'fias_region_id' => $this->faker->uuid(),
            'fias_street_id' => $this->faker->uuid(),
            'fias_house_id' => $this->faker->uuid()
        ];
    }
}
