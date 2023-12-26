<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Checkout\Summary;

use App\Packages\DataTransformers\MoneyTransformer;
use Money\Money;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Schema(schema: 'checkout_summary_data', type: 'object')]
class SummaryData extends Data
{
    public function __construct(
        #[Property(property: 'summary', type: 'string')]
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $summary,
        #[Property(property: 'delivery', type: 'string')]
        #[WithTransformer(MoneyTransformer::class)]
        public readonly Money $delivery
    ) {
    }
}
