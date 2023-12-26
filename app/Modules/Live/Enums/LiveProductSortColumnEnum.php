<?php

declare(strict_types=1);

namespace App\Modules\Live\Enums;

use OpenApi\Attributes\Schema;

#[Schema(schema: 'live_product_sort_column_enum', type: 'string')]
enum LiveProductSortColumnEnum: string
{
    case CREATED_AT = 'created_at';
    case STARTED_AT = 'started_at';
    case EXPIRED_AT = 'expired_at';
    case NUMBER = 'number';
    case POPULAR = 'popular';
}
