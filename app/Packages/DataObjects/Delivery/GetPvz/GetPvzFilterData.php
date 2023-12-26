<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery\GetPvz;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'get_pvz_filter_data', type: 'object')]
class GetPvzFilterData extends Data
{
    public function __construct(
        #[Property('address', ref: '#/components/schemas/get_pvz_address_filter_data', type: 'object', nullable: true)]
        public readonly ?GetPvzAddressFilterData $address = null,
        #[MapName('carrier_ids')]
        #[Property(property: 'carrier_ids', type: 'array', items: new Items(type: 'integer'), nullable: true)]
        public readonly ?array $carrierIds = null,
    ) {
    }
}
