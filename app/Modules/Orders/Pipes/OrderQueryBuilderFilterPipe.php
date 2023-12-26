<?php

declare(strict_types=1);

namespace App\Modules\Orders\Pipes;

use App\Modules\Orders\Contracts\Filters\OrderQueryBuilderFilterContract;
use App\Modules\Orders\Contracts\Pipes\OrderQueryBuilderPipeContract;
use App\Modules\Orders\Filters\OrderQueryBuilderBetweenDatetimeFilter;
use App\Modules\Orders\Filters\OrderQueryBuilderUserIdFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class OrderQueryBuilderFilterPipe implements OrderQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'between_datetime' => OrderQueryBuilderBetweenDatetimeFilter::class,
        'user_id' => OrderQueryBuilderUserIdFilter::class
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        foreach ($this->getRequestFilters() as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var OrderQueryBuilderFilterContract $filterClass */
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
