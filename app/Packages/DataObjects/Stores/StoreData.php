<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use App\Modules\Stores\Models\Store;
use Illuminate\Support\Collection;
use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'store_data',
    description: 'Store data',
    type: 'object'
)]
class StoreData extends Data
{
    public function __construct(
        #[Property(property: 'id', type: 'string')]
        public readonly int $id,
        #[Property(property: 'name', type: 'string')]
        public readonly string $name,
        #[Property(property: 'description', type: 'string')]
        public readonly ?string $description,
        #[Property(property: 'address', type: 'string')]
        public readonly string $address,
        #[Property(property: 'phone', type: 'string')]
        public readonly string $phone,
        #[Property(property: 'latitude', type: 'float')]
        public readonly float $latitude,
        #[Property(property: 'longitude', type: 'float')]
        public readonly float $longitude,
        #[Property(property: 'isWorkWeekdays', type: 'boolean')]
        public readonly bool $isWorkWeekdays,
        #[Property(property: 'isWorkSaturday', type: 'boolean')]
        public readonly bool $isWorkSaturday,
        #[Property(property: 'isWorkSunday', type: 'boolean')]
        public readonly bool $isWorkSunday,
        #[Property(
            property: 'types',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/store_type_data',
            ),
            nullable: true
        )]
        public readonly ?Collection $types = null,
        #[Property(
            property: 'workTimes',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/create_work_time_data',
            ),
            nullable: true
        )]
        public readonly ?Collection $work_times = null,
        #[Property(
            property: 'subways',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/subway_data',
            ),
            nullable: true
        )]
        public readonly ?Collection $subways = null
    )
    {
    }

    public static function fromModel(Store $store)
    {
        $workTimes = new Collection();
        $types = new Collection();
        $subways = new Collection();

        foreach ($store->types as $type) {
            $types->add(StoreTypeData::fromModel($type));
        }

        foreach ($store->workTimes as $workTime) {
            $workTimes->add(WorkTimeData::fromModel($workTime));
        }

        foreach ($store->subways as $subway) {
            $subways->add(SubwayData::fromModel($subway));
        }
        return new self(
            $store->id,
            $store->name,
            $store->description,
            $store->address,
            $store->phone,
            $store->latitude,
            $store->longitude,
            $store->isWorkWeekdays,
            $store->isWorkSaturday,
            $store->isWorkSunday,
            $types,
            $workTimes,
            $subways
        );
    }
}
