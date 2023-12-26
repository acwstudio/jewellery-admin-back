<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use App\Modules\Delivery\Models\Metro;
use App\Modules\Delivery\Models\Pvz;
use App\Packages\DataTransformers\MoneyTransformer;
use Illuminate\Support\Collection;
use Money\Money;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'pvz_data', type: 'object')]
class PvzData extends Data
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
        #[Property(property: 'carrier', ref: '#/components/schemas/carrier_data', type: 'object')]
        public readonly CarrierData $carrier,
        #[Property(property: 'price', type: 'string')]
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $price,
        #[Property(property: 'address', type: 'string')]
        public readonly string $address,
        #[Property(property: 'metro', type: 'array', items: new Items(ref:  '#/components/schemas/metro_data'))]
        public readonly Collection $metro,
    ) {
    }

    public static function fromModel(Pvz $pvz): self
    {
        return new self(
            $pvz->id,
            $pvz->external_id,
            $pvz->latitude,
            $pvz->longitude,
            $pvz->work_time,
            $pvz->area,
            $pvz->city,
            $pvz->district,
            $pvz->street,
            CarrierData::from($pvz->carrier),
            $pvz->price,
            $pvz->address,
            $pvz->metro->map(function (Metro $metro) {
                return MetroData::from($metro);
            }),
        );
    }
}
