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

#[Schema(schema: 'update_pvz_data', type: 'object')]
class UpdatePvzData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'external_id', type: 'string')]
        public readonly string $external_id,
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
