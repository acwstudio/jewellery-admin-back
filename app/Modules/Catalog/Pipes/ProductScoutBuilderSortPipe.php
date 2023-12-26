<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use App\Modules\Catalog\Contracts\Pipes\ProductScoutBuilderPipeContract;
use App\Modules\Catalog\Support\Pipeline\ProductPipelineData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use App\Packages\Enums\Catalog\ProductSortColumnEnum;
use App\Packages\Enums\SortOrderEnum;
use Closure;
use OpenSearch\ScoutDriverPlus\Builders\SearchParametersBuilder;

class ProductScoutBuilderSortPipe implements ProductScoutBuilderPipeContract
{
    public function handle(ProductPipelineData $pipelineData, Closure $next): ProductPipelineData
    {
        $sortOrder = $this->getSortOrder($pipelineData->data);
        $sortColumn = $this->getSortColumn($pipelineData->data);

        match ($sortColumn) {
            ProductSortColumnEnum::POPULARITY => $this->orderByPopularity($pipelineData->builder, $sortOrder),
            ProductSortColumnEnum::PRICE => $this->orderByPriceMin($pipelineData->builder, $sortOrder),
            ProductSortColumnEnum::DISCOUNT => $this->orderByDiscountMax($pipelineData->builder, $sortOrder),
            ProductSortColumnEnum::CREATED_AT => $this->orderByCreatedAt($pipelineData->builder, $sortOrder)
        };

        $pipelineData->builder->sort('id');

        return $next($pipelineData);
    }

    private function getSortColumn(ProductGetListData $data): ProductSortColumnEnum
    {
        if (null !== $data->sort_by) {
            return $data->sort_by;
        }

        return ProductSortColumnEnum::POPULARITY;
    }

    private function getSortOrder(ProductGetListData $data): SortOrderEnum
    {
        if (null !== $data->sort_order) {
            return $data->sort_order;
        }

        return SortOrderEnum::DESC;
    }

    private function orderByPopularity(SearchParametersBuilder $builder, SortOrderEnum $sortOrder): void
    {
        /** TODO включить как будут данные в OpenSearch */
        /* builder->sort('popularity', $sortOrder->value); */
    }

    private function orderByCreatedAt(SearchParametersBuilder $builder, SortOrderEnum $sortOrder): void
    {
        $builder->sort('created_at', $sortOrder->value);
    }

    private function orderByPriceMin(SearchParametersBuilder $builder, SortOrderEnum $sortOrder): void
    {
        $builder->sort('price_min', $sortOrder->value);
    }

    private function orderByDiscountMax(SearchParametersBuilder $builder, SortOrderEnum $sortOrder): void
    {
        $builder->sort('discount_max', $sortOrder->value);
    }
}
