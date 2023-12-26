<?php

declare(strict_types=1);

namespace App\Modules\Orders\Repositories;

use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\Delivery;
use App\Packages\Enums\Orders\DeliveryType;
use Money\Money;

class DeliveryRepository
{
    public function createCurrierDelivery(
        Order $order,
        Money $price,
        string $currierDeliveryId
    ): Delivery {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $order->delivery()->create([
            'price' => $price,
            'delivery_type' => DeliveryType::CURRIER,
            'currier_delivery_id' => $currierDeliveryId
        ]);
    }

    public function createPvzDelivery(
        Order $order,
        Money $price,
        int $pvzId
    ): Delivery {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $order->delivery()->create([
            'price' => $price,
            'delivery_type' => DeliveryType::PVZ,
            'pvz_id' => $pvzId
        ]);
    }
}
