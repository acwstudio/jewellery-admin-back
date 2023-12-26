<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\CreateOrder;

use App\Packages\Enums\Orders\DeliveryType;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'create_order_delivery_data', type: 'object')]
class CreateOrderDeliveryData extends Data
{
    public function __construct(
        #[MapName('delivery_type')]
        #[Property('delivery_type', ref: '#/components/schemas/delivery_type_enum')]
        public readonly DeliveryType $deliveryType,
        #[MapName('pvz_id')]
        #[Property('pvz_id', type: 'integer', nullable: true)]
        public readonly ?int $pvzId = null,
        #[MapName('currier_delivery_id')]
        #[Property('currier_delivery_id', type: 'string', nullable: true)]
        public readonly ?string $currierDeliveryId = null
    ) {
    }
}
