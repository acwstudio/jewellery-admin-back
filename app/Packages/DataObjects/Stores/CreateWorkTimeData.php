<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'create_work_time_data',
    description: 'Create work time data',
    type: 'object'
)]
class CreateWorkTimeData extends Data
{
    public function __construct(
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
