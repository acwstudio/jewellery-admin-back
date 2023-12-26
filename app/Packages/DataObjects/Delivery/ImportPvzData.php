<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery;

use Illuminate\Support\Collection;
use Money\Money;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class ImportPvzData extends Data
{
    public function __construct(
        public readonly string $external_id,
        public readonly string $carrier_name,
        public readonly string $carrier_external_id,
        public readonly string $latitude,
        public readonly string $longitude,
        public readonly string $work_time,
        public readonly string $area,
        public readonly string $city,
        public readonly string $district,
        public readonly string $street,
        public readonly string $address,
        public readonly Money $price,
        /** @var Collection<MetroData> $metro */
        #[DataCollectionOf(MetroData::class)]
        public readonly Collection $metro,
        public readonly bool $delete,
    ) {
    }
}
