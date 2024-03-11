<?php

declare(strict_types=1);

namespace Domain\Catalog\CustomBuilders\Product;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

final class FilterRelatedSizesByBalance implements Filter
{

    public function __invoke(Builder $query, $value, string $property)
    {
        //  the filter by sizes balance in the scope of the product id
        return $query->with(['sizes' => function ($q) use ($value) {
            $q->where('balance', $value)->orderBy('id');
        }]);
    }
}
