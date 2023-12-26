<?php

declare(strict_types=1);

namespace App\Modules\Delivery\Repository;

use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use Money\Money;

class CurrierDeliveryRepository
{
    public function get(string $id): CurrierDelivery
    {
        /** @var CurrierDelivery $currierDelivery */
        $currierDelivery = CurrierDelivery::query()->findOrFail($id);
        return $currierDelivery;
    }

    public function create(
        CurrierDeliveryAddress $currierDeliveryAddress,
        string $carrierId,
        Money $price
    ): CurrierDelivery {
        /** @var CurrierDelivery $currierDelivery */
        $currierDelivery = $currierDeliveryAddress->deliveries()->create([
            'carrier_id' => $carrierId,
            'price' => $price,
        ]);

        return $currierDelivery;
    }
}
