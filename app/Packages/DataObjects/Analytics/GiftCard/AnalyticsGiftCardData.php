<?php

declare(strict_types=1);

namespace App\Packages\DataObjects\Analytics\GiftCard;

use Carbon\Carbon;
use Money\Money;
use Spatie\LaravelData\Data;

class AnalyticsGiftCardData extends Data
{
    public function __construct(
        public readonly string $number,
        public readonly Money $nominal,
        public readonly string $cycle,
        public readonly Carbon $created_at,
        public readonly string $sku
    ) {
    }
}
