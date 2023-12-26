<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Messages\CreateOrder;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class CreateOrderMessageAddressData extends Data
{
    public function __construct(
        #[MapName('region_fias_id')]
        public readonly ?string $regionFiasId = null,
        #[MapName('street_fias_id')]
        public readonly ?string $streetFiasId = null,
        #[MapName('house_fias_id')]
        public readonly ?string $houseFiasId = null,
        #[MapName('zip_code')]
        public readonly ?string $zipCode = null,
        public readonly ?string $region = null,
        public readonly ?string $settlement = null,
        public readonly ?string $city = null,
        public readonly ?string $street = null,
        #[MapName('building')]
        public readonly ?string $house = null,
        public readonly ?string $flat = null,
        #[MapName('korpus')]
        public readonly ?string $block = null,
        #[MapName('Number_PVZ')]
        public readonly ?string $pvzId = null,
    ) {
    }
}
