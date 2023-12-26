<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Pipes;

use App\Modules\Catalog\Contracts\Filters\FilterFeatureQueryBuilderContract;
use App\Modules\Catalog\Contracts\Pipes\FeatureQueryBuilderPipeContract;
use App\Modules\Catalog\Filters\Feature\FeatureQueryBuilderSlugFilter;
use App\Modules\Catalog\Filters\Feature\FeatureQueryBuilderTypeFilter;
use App\Modules\Catalog\Filters\Feature\FeatureQueryBuilderValueFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class FilterFeatureQueryBuilderPipe implements FeatureQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'type' => FeatureQueryBuilderTypeFilter::class,
        'value' => FeatureQueryBuilderValueFilter::class,
        'slug' => FeatureQueryBuilderSlugFilter::class
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        foreach ($this->getRequestFilters() as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var FilterFeatureQueryBuilderContract $filterClass */
                $filterClass = new $this->filters[$name]();
                $query = $filterClass->apply($query, $value);
            }
        }

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
}
