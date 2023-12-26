<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class CreatePromotion extends Data
{
    public function __construct(
        public readonly CreatePromotionPromotion $promotion,
        #[MapInputName('conditions')]
        public readonly CreatePromotionCondition $condition,
        #[MapInputName('sale')]
        #[DataCollectionOf(CreatePromotionBenefit::class)]
        public readonly DataCollection $benefits
    ) {
    }
}
