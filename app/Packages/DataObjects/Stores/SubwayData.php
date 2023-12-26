<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Stores;

use App\Modules\Stores\Enums\StoreWorkDayEnum;
use App\Modules\Stores\Models\StoreWorkTime;
use App\Modules\Stores\Models\Subway;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(
    schema: 'subway_data',
    description: 'Subway data',
    type: 'object'
)]
class SubwayData extends Data
{
    public function __construct(
        #[Property(property: 'id', type:'integer')]
        public readonly int $id,
        #[Property(property: 'line')]
        public readonly string $line,
        #[Property(property: 'station')]
        public readonly string $station,
        #[Property(property: 'color')]
        public readonly string $color,
        #[Property(property: 'distance')]
        public readonly string $distance,
    )
    {
    }

    public static function fromModel(Subway $subway)
    {
        return new self(
            $subway->id,
            $subway->line,
            $subway->station,
            $subway->color,
            $subway->pivot->distance
        );
    }
}
