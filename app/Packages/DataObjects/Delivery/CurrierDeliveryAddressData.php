<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'currier_delivery_address_data', type: 'object')]
class CurrierDeliveryAddressData extends Data
{
    public function __construct(
        #[MapName('full_address')]
        #[Property('full_address', type: 'string')]
        public readonly string $fullAddress,
        #[MapName('region_fias_id')]
        #[Property(property: 'region_fias_id', type: 'string')]
        public readonly string $regionFiasId,
        #[MapName('street_fias_id')]
        #[Property(property: 'street_fias_id', type: 'string')]
        public readonly string $streetFiasId,
        #[MapName('house_fias_id')]
        #[Property(property: 'house_fias_id', type: 'string')]
        public readonly string $houseFiasId,
        #[MapName('zip_code')]
        #[Property(property: 'zip_code', type: 'string')]
        public readonly int $zipCode,
        #[Property(property: 'region', type: 'string')]
        public readonly string $region,
        #[Property(property: 'city', type: 'string')]
        public readonly string $city,
        #[Property(property: 'street', type: 'string')]
        public readonly string $street,
        #[Property(property: 'house', type: 'string')]
        public readonly string $house,
        #[Property(property: 'settlement', type: 'string')]
        public readonly ?string $settlement = null,
        #[Property(property: 'flat', type: 'string')]
        public readonly ?string $flat = null,
        #[Property(property: 'block', type: 'string')]
        public readonly ?string $block = null
    ) {
    }

    public static function fromModel(CurrierDeliveryAddress $address): self
    {
        return new self(
            $address->address,
            $address->fias_region_id,
            $address->fias_street_id,
            $address->fias_house_id,
            $address->zip_code,
            $address->region,
            $address->city,
            $address->street,
            $address->house,
            $address->settlement,
            $address->flat,
            $address->block
        );
    }
}
