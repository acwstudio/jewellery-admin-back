<?php

declare(strict_types=1);

namespace App\Packages\Enums\Catalog;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: OfferStockReasonEnum::NEW)]
enum OfferStockReasonEnum: string
{
    case NEW = 'new';
    case MANUAL = 'manual';
    case RESERVATION = 'reservation';
}
