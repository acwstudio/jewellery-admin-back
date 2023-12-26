<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;

class CreatePromotionBenefitGift extends Data
{
    public function __construct(
        #[MapInputName('UID')]
        #[MapOutputName('external_id')]
        public readonly string $externalId,
        public readonly string $size,
        public readonly int $count
    ) {
    }
}
