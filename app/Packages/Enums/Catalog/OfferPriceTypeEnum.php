<?php

declare(strict_types=1);

namespace App\Packages\Enums\Catalog;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: OfferPriceTypeEnum::PROMO)]
enum OfferPriceTypeEnum: string
{
    case PROMO = 'promo';
    case EMPLOYEE = 'employee';
    case REGULAR = 'regular';
    case LIVE = 'live';
    case PROMOCODE = 'promocode';
    case SALE = 'sale';

    public function getSortNumber(): int
    {
        return match ($this) {
            self::REGULAR => 1,
            self::PROMO => 2,
            self::EMPLOYEE => 3,
            self::LIVE => 4,
            self::SALE => 5,
            default => 99
        };
    }
}
