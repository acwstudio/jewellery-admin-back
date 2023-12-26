<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout\Summary;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

#[Schema(schema: 'get_summary_data', type: 'object')]
class GetSummaryData extends Data
{
    public function __construct(
        #[MapName('delivery')]
        #[Property(
            property: 'delivery',
            ref: '#/components/schemas/get_summary_delivery_data',
            type: 'object',
        )]
        public readonly GetSummaryDeliveryData $deliveryData,
    ) {
    }
}
