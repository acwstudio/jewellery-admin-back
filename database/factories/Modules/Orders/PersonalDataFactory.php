<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Orders;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\PersonalData;
use App\Packages\Support\PhoneNumberTransformer;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalDataFactory extends Factory
{
    protected $model = PersonalData::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'phone' => PhoneNumberTransformer::parse($this->faker->phoneNumber),
            'email' => $this->faker->email,
            'name' => $this->faker->name,
            'surname' => $this->faker->firstName
        ];
    }
}
