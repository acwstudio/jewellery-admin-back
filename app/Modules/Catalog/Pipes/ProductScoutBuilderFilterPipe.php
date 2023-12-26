<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use App\Modules\Catalog\Contracts\Filters\ProductScoutBuilderFilterContract;
use App\Modules\Catalog\Contracts\Pipes\ProductScoutBuilderPipeContract;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderBrandFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderCategoryFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderExcludeSkuFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderFeatureFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderHasImageFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderIdFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderInStockFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderIsActiveFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderOfferPriceTypeFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderPriceFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderPricesFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderQtyInStockFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderSearchFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderSizeFilter;
use App\Modules\Catalog\Filters\Scout\ProductScoutBuilderSkuFilter;
use App\Modules\Catalog\Support\Pipeline\ProductPipelineData;
use App\Packages\DataObjects\Catalog\Product\ProductGetListData;
use Closure;
use OpenSearch\ScoutDriverPlus\Builders\BoolQueryBuilder;
use OpenSearch\ScoutDriverPlus\Support\Query;

class ProductScoutBuilderFilterPipe implements ProductScoutBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'price' => ProductScoutBuilderPriceFilter::class,
        'prices' => ProductScoutBuilderPricesFilter::class,
        'size' => ProductScoutBuilderSizeFilter::class,
        'qty_in_stock' => ProductScoutBuilderQtyInStockFilter::class,
        'in_stock' => ProductScoutBuilderInStockFilter::class,
        'category' => ProductScoutBuilderCategoryFilter::class,
        'brands' => ProductScoutBuilderBrandFilter::class,
        'ids' => ProductScoutBuilderIdFilter::class,
        'feature' => ProductScoutBuilderFeatureFilter::class,
        'sku' => ProductScoutBuilderSkuFilter::class,
        'has_image' => ProductScoutBuilderHasImageFilter::class,
        'is_active' => ProductScoutBuilderIsActiveFilter::class,
        'search' => ProductScoutBuilderSearchFilter::class,
        'exclude_sku' => ProductScoutBuilderExcludeSkuFilter::class,
        'offer_price_type' => ProductScoutBuilderOfferPriceTypeFilter::class,
    ];

    public function handle(ProductPipelineData $pipelineData, Closure $next): ProductPipelineData
    {
        $filter = $this->getRequestFilters($pipelineData->data);
        $this->prepareFilter($filter);
        $queryBuilder = Query::bool();

        foreach ($filter as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var ProductScoutBuilderFilterContract $filterClass */
                $filterClass = new $this->filters[$name]();
                $filterClass->apply($queryBuilder, $value);
            }
        }

        $queryBuilder->must(Query::matchAll());
        $queryBuilder->mustNot(Query::term()->field('price_min')->value(0));
        $this->commonFilter($queryBuilder, $filter);

        $pipelineData->builder->query($queryBuilder);
        return $next($pipelineData);
    }

    private function getRequestFilters(ProductGetListData $data): array
    {
        $dataArray = $data->toArray();

        if (!array_key_exists(self::FILTER_NAME, $dataArray) || !is_array($dataArray[self::FILTER_NAME])) {
            return [];
        }

        $requestFilters = $dataArray[self::FILTER_NAME];

        $requestFilters = array_filter($requestFilters, fn ($elem) => !is_null($elem));

        return array_filter(
            $requestFilters,
            fn ($key) => array_key_exists($key, $this->filters),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function prepareFilter(array &$filter): void
    {
        foreach ($filter as $name => $value) {
            if ('prices' === $name && array_key_exists('price', $filter)) {
                unset($filter['prices']);
            }
        }
    }

    private function commonFilter(BoolQueryBuilder $builder, array $filter): void
    {
        if (!empty($filter['ignore_common'])) {
            return;
        }

        if (
            empty($filter['is_active'])
            && (empty($filter['ids']) || empty($filter['sku']))
        ) {
            $builder->filter(
                Query::term()->field('is_active')->value(true)
            );
        }

        if (empty($filter['price']) && empty($filter['offer_price_type'])) {
            /** Только продукты у которых есть минимальная цена > 0 */
            $builder->filter(
                Query::range()->field('price_min')->gt(0)
            );
        }
    }
}
