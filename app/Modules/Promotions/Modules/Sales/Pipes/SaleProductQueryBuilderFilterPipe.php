<?php

declare(strict_types=1);

namespace App\Modules\Promotions\Modules\Sales\Pipes;

use App\Modules\Promotions\Modules\Sales\Contracts\Filters\SaleProductQueryBuilderFilterContract;
use App\Modules\Promotions\Modules\Sales\Contracts\Pipes\SaleProductQueryBuilderPipeContract;
use App\Modules\Promotions\Modules\Sales\Filters\SaleProductQueryBuilderProductIdFilter;
use App\Modules\Promotions\Modules\Sales\Filters\SaleProductQueryBuilderSaleIsActiveFilter;
use App\Modules\Promotions\Modules\Sales\Filters\SaleProductQueryBuilderSaleIdFilter;
use App\Modules\Promotions\Modules\Sales\Filters\SaleProductQueryBuilderSaleSlugFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class SaleProductQueryBuilderFilterPipe implements SaleProductQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'sale_id' => SaleProductQueryBuilderSaleIdFilter::class,
        'sale_slug' => SaleProductQueryBuilderSaleSlugFilter::class,
        'product_id' => SaleProductQueryBuilderProductIdFilter::class,
        'is_active' => SaleProductQueryBuilderSaleIsActiveFilter::class
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        $filters = $this->getRequestFilters();
        foreach ($filters as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var SaleProductQueryBuilderFilterContract $filterClass */
                $filterClass = new $this->filters[$name]();
                $query = $filterClass->apply($query, $value);
            }
        }

        $this->commonFilter($query, $filters);

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

    private function commonFilter(Builder $query, array $requestParams): void
    {
    }
}
