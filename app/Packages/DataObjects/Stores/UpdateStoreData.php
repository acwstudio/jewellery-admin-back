<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(
    schema: 'update_store_data',
    description: 'Update store',
    type: 'object'
)]
class UpdateStoreData extends Data
{
    public function __construct(
        #[Property(property: 'name', type: 'string')]
        #[Required, StringType]
        public readonly string $name,
        #[Property(property: 'description', type: 'string')]
        #[StringType, Nullable]
        public readonly ?string $description,
        #[Property(property: 'address', type: 'string')]
        #[Required, StringType]
        public readonly string $address,
        #[Property(property: 'phone', type: 'string')]
        #[Required, StringType]
        public readonly string $phone,
        #[Property(property: 'latitude', type: 'float')]
        #[Required, Numeric]
        public readonly float $latitude,
        #[Property(property: 'longitude', type: 'float')]
        #[Required, Numeric]
        public readonly float $longitude,
//        #[Property(property: 'types', type: 'array')]
        #[Nullable, ArrayType]
        public readonly ?array $types = [],
        #[Property(
            property: 'work_times',
            type: 'array',
            items: new Items(
                ref: '#/components/schemas/create_or_update_work_time_data',
            ),
            nullable: true
        )]
        #[Nullable, DataCollectionOf(CreateOrUpdateWorkTimeData::class)]
        public readonly array $work_times = [],
        #[Property(property: 'isWorkSaturday', type: 'boolean')]
        public bool $isWorkSaturday = false,
        #[Property(property: 'isWorkSunday', type: 'boolean')]
        public bool $isWorkSunday = false,
    ) {
    }

    public static function rules(): array
    {
        return [
            'types.*' => ['numeric', 'exists:App\Modules\Stores\Models\StoreType,id'],
            'work_times.*.id' => ['nullable', 'exists:App\Modules\Stores\Models\StoreWorkTime,id', 'bail'],
            'work_times.*.day' => ['string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday', 'bail'],
            'work_times.*.start_time' => ['date_format:H:i', 'bail'],
            'work_times.*.end_time' => ['date_format:H:i', 'bail']
        ];
    }
}
