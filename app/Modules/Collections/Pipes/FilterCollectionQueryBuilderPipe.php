<?php

declare(strict_types=1);

namespace App\Modules\Collections\Pipes;

use App\Modules\Collections\Contracts\Filters\FilterCollectionQueryBuilderContract;
use App\Modules\Collections\Contracts\Pipes\CollectionQueryBuilderPipeContract;
use App\Modules\Collections\Filters\CollectionQueryBuilderNameFilter;
use App\Modules\Collections\Filters\CollectionQueryBuilderStoneFilter;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class FilterCollectionQueryBuilderPipe implements CollectionQueryBuilderPipeContract
{
    private const FILTER_NAME = 'filter';

    private array $filters = [
        'stone' => CollectionQueryBuilderStoneFilter::class,
        'name' => CollectionQueryBuilderNameFilter::class
    ];

    public function handle(Builder $query, Closure $next): Builder
    {
        foreach ($this->getRequestFilters() as $name => $value) {
            if (class_exists($this->filters[$name])) {
                /** @var FilterCollectionQueryBuilderContract $filterClass */
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
        $query->where('is_active', '=', true);
        $query->where('is_hidden', '=', false);

        if (empty($requestParams['name'])) {
            $query->whereDoesntHave('favorite');
        }

        /** TODO Удалить после добавления в БД is_hidden = true для этих коллекций */
        $excludeIds = config('collections.exclude_ids');
        if (!empty($excludeIds)) {
            $query->whereNotIn('id', explode(',', $excludeIds));
        }
    }
}
