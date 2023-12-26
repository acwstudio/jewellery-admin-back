<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout\Summary;

use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Attributes\Validation\ProhibitedIf;
use Spatie\LaravelData\Data;

#[Schema(schema: 'get_summary_delivery_data', type: 'object')]
class GetSummaryDeliveryData extends Data
{
    public function __construct(
        #[MapName('currier_delivery_id')]
        #[Property('currier_delivery_id', type: 'string', nullable: true)]
        public readonly ?string $currierDeliveryId = null,
        #[MapName('pvz_id')]
        #[Property(property: 'pvz_id', type: 'integer', nullable: true)]
        public readonly ?int $pvzId = null
    ) {
    }
}
