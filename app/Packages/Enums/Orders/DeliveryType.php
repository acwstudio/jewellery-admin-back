<?php

declare(strict_types=1);

namespace App\Packages\Enums\Orders;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'delivery_type_enum', type: 'string')]
enum DeliveryType: string
{
    case CURRIER = 'currier';
    case PVZ = 'pvz';
}
