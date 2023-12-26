<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Filter;

use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\GreaterThanOrEqualTo;
use Spatie\LaravelData\Data;
use Symfony\Contracts\Service\Attribute\Required;

#[Schema(schema: 'orders_filter_between_datetime_data', type: 'object')]
class BetweenDatetimeData extends Data
{
    public function __construct(
        #[Property(property: 'start', type: 'datetime')]
        #[
            Required,
            Date
        ]
        public readonly Carbon $start,
        #[Property(property: 'end', type: 'integer')]
        #[
            Required,
            Date,
            GreaterThanOrEqualTo('start')
        ]
        public readonly Carbon $end
    ) {
    }
}
