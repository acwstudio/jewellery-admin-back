<?php

declare(strict_types=1);

namespace App\Modules\Live\Pipes;

use App\Modules\Live\Contracts\Filters\LiveProductQueryBuilderFilterContract;
use App\Modules\Live\Contracts\Pipes\LiveProductQueryBuilderPipeContract;
use App\Modules\Live\Filters\LiveProductQueryBuilderIsActiveFilter;
use App\Modules\Live\Filters\LiveProductQueryBuilderLastDaysFilter;
use App\Modules\Live\Filters\LiveProductQueryBuilderOnLiveFilter;
use App\Modules\Live\Filters\LiveProductQueryBuilderProductIdFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class LiveProductQueryBuilderFilterPipe implements LiveProductQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'is_active' => LiveProductQueryBuilderIsActiveFilter::class,
        'ids' => LiveProductQueryBuilderProductIdFilter::class,
        'on_live' => LiveProductQueryBuilderOnLiveFilter::class,
        'last_days' => LiveProductQueryBuilderLastDaysFilter::class,
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        foreach ($this->getRequestFilters() as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var LiveProductQueryBuilderFilterContract $filterClass */
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
