<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery\GetPvz;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'get_pvz_address_filter_data', type: 'object')]
class GetPvzAddressFilterData extends Data
{
    public function __construct(
        #[Property('city', type: 'string')]
        public readonly string $city,
        #[Property('street', type: 'string', nullable: true)]
        public readonly ?string $street = null,
        #[Property('district', type: 'string', nullable: true)]
        public readonly ?string $district = null,
    ) {
    }
}
