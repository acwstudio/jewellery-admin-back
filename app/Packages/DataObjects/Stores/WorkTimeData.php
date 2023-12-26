<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use App\Modules\Catalog\Models\Brand;
use App\Modules\Stores\Models\Store;
use App\Modules\Stores\Enums\StoreWorkDayEnum;
use App\Modules\Stores\Models\StoreWorkTime;
use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'work_time_data',
    description: 'Work time data',
    type: 'object'
)]
class WorkTimeData extends Data
{
    public function __construct(
        #[Property(property: 'id', type:'integer')]
        public readonly int $id,
        #[Property(property: 'day')]
        public readonly StoreWorkDayEnum $day,
        #[Property(property: 'start_time', type: 'time')]
        public readonly string $start_time,
        #[Property(property: 'end_time', type: 'time')]
        public readonly string $end_time,
    )
    {
    }

    public static function fromModel(StoreWorkTime $workTimeData)
    {
        return new self(
            $workTimeData->id,
            $workTimeData->day,
            $workTimeData->start_time,
            $workTimeData->end_time
        );
    }
}
