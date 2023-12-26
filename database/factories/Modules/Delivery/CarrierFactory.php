<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Delivery;

use App\Modules\Delivery\Models\Carrier;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'external_id' => $this->faker->uuid(),
            'name' => $this->faker->userName(),
        ];
    }
}
