<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Order;

use App\Packages\Enums\Orders\DeliveryType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'order_delivery_data', type: 'object')]
class OrderDeliveryData extends Data
{
    public function __construct(
        #[MapName('delivery_type')]
        #[Property('delivery_type', ref: '#/components/schemas/delivery_type_enum')]
        public readonly DeliveryType $deliveryType,
        #[MapName('currier_delivery_address')]
        #[Property('currier_delivery_address', nullable: true)]
        public readonly ?string $currierDeliveryAddress = null,
        #[Property('pvz', ref: '#/components/schemas/order_delivery_pvz_data', type: 'object')]
        public readonly ?OrderDeliveryPvzData $pvz = null
    ) {
    }
}
