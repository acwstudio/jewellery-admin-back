<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use App\Modules\Stores\Models\StoreWorkTime;
use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'create_or_update_work_time_data',
    description: 'Refresh work time data',
    type: 'object'
)]
class CreateOrUpdateWorkTimeData extends Data
{
    public function __construct(
        #[Property(property: 'id')]
        #[Nullable, IntegerType, Exists(StoreWorkTime::class)]
        public readonly ?int $id,
        #[Property(property: 'day')]
        #[Required, Enum(StoreWorkDayEnum::class)]
        public readonly string $day,
        #[Property(property: 'start_time', type: 'time')]
        #[Required]
        public readonly string $start_time,
        #[Property(property: 'end_time', type: 'time')]
        #[Required]
        public readonly string $end_time,
    )
    {
    }
}
