<?php

declare(strict_types=1);

namespace Domain\Catalog\CustomBuilders\Product;

use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

final class ProductBuilder extends QueryBuilder
{
    public function virtualProductCategoryName(): self
    {
        return $this->addSelect('*', DB::raw("(SELECT name FROM product_categories where product_category_id = id)
        as product_category_name"));
    }
}
