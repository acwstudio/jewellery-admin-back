<?php

declare(strict_types=1);

namespace Database\Factories\Modules\Delivery;

use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Money\Money;

class CurrierDeliveryFactory extends Factory
{
    protected $model = CurrierDelivery::class;

    /**
     * @inheritDoc
     */
    public function definition()
    {
        return [
            'carrier_id' => Carrier::factory(),
            'price' => Money::RUB(rand(1000, 10000) * 100),
            'currier_delivery_address_id' => CurrierDeliveryAddress::factory()
        ];
    }
}
