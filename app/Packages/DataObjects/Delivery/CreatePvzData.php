<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use App\Packages\DataCasts\MoneyCast;
use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'create_pvz_data', type: 'object')]
class CreatePvzData extends Data
{
    public function __construct(
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id,
        #[Property(property: 'carrier_id', type: 'int')]
        public readonly int $carrier_id,
        #[Property(property: 'latitude', type: 'string')]
        public readonly string $latitude,
        #[Property(property: 'longitude', type: 'string')]
        public readonly string $longitude,
        #[Property(property: 'work_time', type: 'string')]
        public readonly string $work_time,
        #[Property(property: 'area', type: 'string')]
        public readonly string $area,
        #[Property(property: 'city', type: 'string')]
        public readonly string $city,
        #[Property(property: 'district', type: 'string')]
        public readonly string $district,
        #[Property(property: 'street', type: 'string')]
        public readonly string $street,
        #[Property(property: 'address', type: 'string')]
        public readonly string $address,
        #[Property(property: 'price', type: 'string')]
        #[WithCast(MoneyCast::class)]
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $price
    ) {
    }
}
