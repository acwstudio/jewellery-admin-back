<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Order;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'order_delivery_pvz_data', type: 'object')]
class OrderDeliveryPvzData extends Data
{
    public function __construct(
        #[Property('address', type: 'string')]
        public readonly string $address,
        #[Property('schedule', type: 'string')]
        public readonly string $schedule
    ) {
    }
}
