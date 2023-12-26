<?php

declare(strict_types=1);

namespace App\Packages\Enums\Catalog;

use OpenApi\Attributes\Schema;

#[Schema(type: 'string', example: OfferReservationStatusEnum::PENDING)]
enum OfferReservationStatusEnum: string
{
    case PENDING = 'pending';
    case PURCHASED = 'purchased';
    case CANCELED = 'canceled';
}
