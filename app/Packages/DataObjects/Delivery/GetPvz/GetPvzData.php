<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Delivery\GetPvz;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'get_pvz_data', type: 'object')]
class GetPvzData extends Data
{
    public function __construct(
        #[Property('filter', ref: '#/components/schemas/get_pvz_filter_data', type: 'object', nullable: true)]
        public readonly ?GetPvzFilterData $filter = null,
    ) {
    }
}
