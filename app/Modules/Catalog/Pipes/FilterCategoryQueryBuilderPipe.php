<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use App\Modules\Catalog\Contracts\Pipes\CategoryQueryBuilderPipeContract;
use App\Modules\Catalog\Filters\Category\CategoryQueryBuilderHasProductFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class FilterCategoryQueryBuilderPipe implements CategoryQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'has_product' => CategoryQueryBuilderHasProductFilter::class,
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        foreach ($this->getRequestFilters() as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var FilterProductQueryBuilderContract $filterClass */
                $filterClass = new $this->filters[$name]();
                $query = $filterClass->apply($query, $value);
            }
        }

        $this->commonFilter($query, $this->getRequestFilters());

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
