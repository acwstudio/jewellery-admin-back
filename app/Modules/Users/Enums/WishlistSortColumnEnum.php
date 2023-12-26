<?php

declare(strict_types=1);

namespace App\Modules\Users\Enums;

use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'users_wishlist_sort_column',
    description: 'Поля сортировки избранного',
    type: 'string',
)]
enum WishlistSortColumnEnum: string
{
    case CREATED_AT = 'created_at';

    case PRODUCT_ID = 'product_id';
}
