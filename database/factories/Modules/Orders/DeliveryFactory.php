<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Orders;

use App\Modules\Orders\Models\Delivery;
use App\Modules\Orders\Models\Order;
use App\Packages\Enums\Orders\DeliveryType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'delivery_type' => $this->faker->randomElement(DeliveryType::cases())->value,
            'price' => Money::RUB(rand(100, 1000) * 100),
        ];
    }
}
