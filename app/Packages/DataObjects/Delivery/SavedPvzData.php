<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use App\Modules\Delivery\Models\Pvz;
use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'saved_pvz_data', type: 'object')]
class SavedPvzData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'integer')]
        public readonly int $id,
        #[Property(property: 'latitude', type: 'string')]
        public readonly string $latitude,
        #[Property(property: 'longitude', type: 'string')]
        public readonly string $longitude,
        #[Property(property: 'work_time', type: 'string')]
        public readonly string $work_time,
        #[Property(property: 'carrier', ref: '#/components/schemas/carrier_data', type: 'object')]
        public readonly CarrierData $carrier,
        #[Property(property: 'price', type: 'string')]
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $price,
        #[Property(property: 'address', type: 'string')]
        public readonly string $address,
    ) {
    }

    public static function fromModel(Pvz $pvz): self
    {
        return new self(
            $pvz->id,
            $pvz->latitude,
            $pvz->longitude,
            $pvz->work_time,
            CarrierData::from($pvz->carrier),
            $pvz->price,
            $pvz->address
        );
    }
}
