<?php

declare(strict_types=1);

namespace App\Modules\Collections\Pipes;

use App\Modules\Collections\Contracts\Filters\FilterStoneQueryBuilderContract;
use App\Modules\Collections\Contracts\Pipes\StoneQueryBuilderPipeContract;
use App\Modules\Collections\Filters\StoneQueryBuilderNameFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class FilterStoneQueryBuilderPipe implements StoneQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'name' => StoneQueryBuilderNameFilter::class
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        foreach ($this->getRequestFilters() as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var FilterStoneQueryBuilderContract $filterClass */
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
