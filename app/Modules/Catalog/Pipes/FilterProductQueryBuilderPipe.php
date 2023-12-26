<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use App\Modules\Catalog\Filters\ProductQueryBuilderBrandFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderCategoryFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderExcludeSkuFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderFeatureFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderHasImageFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderIdFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderIsActiveFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderOfferPriceTypeFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderPricesFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderPriceFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderInStockFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderQtyInStockFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderSearchFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderSizeFilter;
use App\Modules\Catalog\Filters\ProductQueryBuilderSkuFilter;
use Closure;
use App\Modules\Catalog\Contracts\Pipes\ProductQueryBuilderPipeContract;
use Illuminate\Database\Eloquent\Builder;

class FilterProductQueryBuilderPipe implements ProductQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'price' => ProductQueryBuilderPriceFilter::class,
        'prices' => ProductQueryBuilderPricesFilter::class,
        'size' => ProductQueryBuilderSizeFilter::class,
        'qty_in_stock' => ProductQueryBuilderQtyInStockFilter::class,
        'in_stock' => ProductQueryBuilderInStockFilter::class,
        'category' => ProductQueryBuilderCategoryFilter::class,
        'brands' => ProductQueryBuilderBrandFilter::class,
        'ids' => ProductQueryBuilderIdFilter::class,
        'feature' => ProductQueryBuilderFeatureFilter::class,
        'sku' => ProductQueryBuilderSkuFilter::class,
        'has_image' => ProductQueryBuilderHasImageFilter::class,
        'is_active' => ProductQueryBuilderIsActiveFilter::class,
        'search' => ProductQueryBuilderSearchFilter::class,
        'exclude_sku' => ProductQueryBuilderExcludeSkuFilter::class,
        'offer_price_type' => ProductQueryBuilderOfferPriceTypeFilter::class,
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        $filter = $this->getRequestFilters();
        $this->prepareFilter($filter);

        foreach ($filter as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var FilterProductQueryBuilderContract $filterClass */
                $filterClass = new $this->filters[$name]();
                $query = $filterClass->apply($query, $value);
            }
        }

        $this->commonFilter($query, $filter);

        return $next($query);
    }

    private function getRequestFilters(): array
    {
        if (!request()->has(self::FILTER_NAME) || !is_array(request(self::FILTER_NAME))) {
            return [];
        }

        $requestFilters = request(self::FILTER_NAME);

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

    private function commonFilter(Builder $query, array $filter): void
    {
        if (!empty($filter['ignore_common'])) {
            return;
        }

        if (
            empty($filter['is_active'])
            && (empty($filter['ids']) || empty($filter['sku']))
        ) {
            $query->where('is_active', '=', true);
        }

        if (empty($filter['price']) && empty($filter['offer_price_type'])) {
            /** Только продукты у которых есть торговые предложения с ценами */
            $query->whereHas(
                'productOffers.productOfferPrices',
                fn (Builder $productOfferPriceBuilder) => $productOfferPriceBuilder
                    ->where('is_active', '=', true)
            );
        }
    }
}
