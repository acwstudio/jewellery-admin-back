<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Promotions;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class UpdatePromotion extends Data
{
    public function __construct(
        #[MapOutputName('external_id')]
        public readonly string $externalId,
        public readonly string $description,
        #[MapOutputName('is_active')]
        public readonly bool $isActive,
    ) {
    }
}
