<?php

declare(strict_types=1);

namespace App\Packages\Enums\Catalog;

use OpenApi\Attributes\Schema;

#[Schema(
    schema: 'catalog_product_sort_column',
    description: 'Поля сортировки продукта',
    type: 'string',
    default: self::POPULARITY
)]
enum ProductSortColumnEnum: string
{
    case POPULARITY = 'popularity';
    case PRICE = 'price';
    case DISCOUNT = 'discount';
    case CREATED_AT = 'created_at';
}
