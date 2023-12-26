<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Live\LiveProduct;

use Carbon\Carbon;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Data;

#[Schema(schema: 'live_create_live_product_data', type: 'object')]
class CreateLiveProductData extends Data
{
    public function __construct(
        #[Property(property: 'product_id', type: 'integer')]
        public readonly int $product_id,
        #[Property(property: 'number', type: 'integer')]
        public readonly int $number,
        #[Property(property: 'started_at', type: 'string', format: 'date-time', example: '2023-03-09T10:56:00+00:00')]
        public readonly Carbon $started_at,
        #[Property(
            property: 'expired_at',
            type: 'string',
            format: 'date-time',
            example: '2023-03-09T10:56:00+00:00',
            nullable: true
        )]
        public readonly ?Carbon $expired_at = null,
        #[Property(property: 'on_live', type: 'boolean', nullable: true)]
        public readonly ?bool $on_live = null,
    ) {
    }
}
