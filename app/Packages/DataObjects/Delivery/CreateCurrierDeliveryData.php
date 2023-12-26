<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'create_currier_delivery_data', type: 'object')]
class CreateCurrierDeliveryData extends Data
{
    public function __construct(
        #[MapName('delivery_address_id')]
        #[Property('delivery_address_id', type: 'integer', nullable: true)]
        public readonly ?int $deliveryAddressId = null,
        #[MapName('delivery_address')]
        #[Property(
            'delivery_address',
            ref: '#/components/schemas/currier_delivery_address_data',
            type: 'object',
            nullable: true
        )]
        public readonly ?CurrierDeliveryAddressData $deliveryAddress = null
    ) {
    }
}
