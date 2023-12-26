<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Orders\Filter;

use App\Packages\Support\FilterQuery\Attributes\Nullable;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Data;

#[Schema(schema: 'orders_filter_order_data', type: 'object')]
class FilterOrderData extends Data
{
    public function __construct(
        /** @codingStandardsIgnoreStart */
        #[Property(property: 'between_datetime', type: 'datetime', nullable: true)]
        #[Nullable, ArrayType('start', 'end')]
        public readonly ?BetweenDatetimeData $between_datetime = null,
        #[Property(property: 'user_id', type: 'string', nullable: true)]
        public readonly ?string $user_id = null,
        /** @codingStandardsIgnoreEnd */
    ) {
    }
}
