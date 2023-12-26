<?php

declare(strict_types=1);

namespace App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost;

use App\Packages\DataCasts\MoneyCast;
use Money\Money;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

class DeliveryCostData extends Data
{
    public function __construct(
        public readonly string $id,
        #[WithCast(MoneyCast::class, isDecimal: true)]
        public readonly Money $cost
    ) {
    }
}
