<?php

declare(strict_types=1);

namespace App\Packages\Enums\Orders;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'orders_order_sort_column', type: 'string')]
enum OrderSortColumnEnum: string
{
    case SUMMARY = 'summary';
    case CREATED_AT = 'created_at';
}
