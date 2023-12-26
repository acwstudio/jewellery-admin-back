<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Contracts\Providers\ProductFilterProviderContract;
use App\Modules\Catalog\Providers\Feature\FeatureBooleanProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureDesignProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureInsertColorProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureInsertProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureMetalColorProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureMetalProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureOccasionProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureProbeProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureSexProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureShapeProductFilterProvider;
use App\Modules\Catalog\Providers\Feature\FeatureStyleProductFilterProvider;
use App\Modules\Catalog\Providers\FromDBProductFilterProvider;
use App\Modules\Catalog\Providers\PriceProductFilterProvider;
use App\Modules\Catalog\Providers\SizeProductFilterProvider;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterListData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use Carbon\CarbonInterval;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CatalogProductFilterService
{
    private array $filters = [
        2 => FeatureMetalProductFilterProvider::class,
        3 => FeatureInsertProductFilterProvider::class,
        4 => FeatureMetalColorProductFilterProvider::class,
        11 => PriceProductFilterProvider::class,
        12 => FeatureBooleanProductFilterProvider::class,
        5 => FeatureSexProductFilterProvider::class,
        7 => SizeProductFilterProvider::class,
        6 => FeatureProbeProductFilterProvider::class,
        13 => FeatureInsertColorProductFilterProvider::class,
        14 => FeatureShapeProductFilterProvider::class,
        8 => FeatureDesignProductFilterProvider::class,
        9 => FeatureStyleProductFilterProvider::class,
        10 => FeatureOccasionProductFilterProvider::class,
    ];

    public function getList(GetListProductFilterData $data): CatalogProductFilterListData
    {
        return Cache::remember('catalog.filter', CarbonInterval::day(), function () use ($data) {
            $filters = new Collection();

            foreach ($this->filters as $key => $filterName) {
                /** @var ProductFilterProviderContract $filter */
                $filter = new $filterName();
                $filterData = $filter->get($key, false, $data);
                if ($this->isExistParams($filterData)) {
                    $filters->add($filterData);
                }
            }

            return CatalogProductFilterListData::fromCollection($filters);
        });
    }

    public function getListStatic(): CatalogProductFilterListData
    {
        $collection = new Collection();

        $filters = $this->filters;
        ksort($filters);

        foreach ($filters as $key => $filterName) {
            /** @var ProductFilterProviderContract $filter */
            $filter = new $filterName();

            if (!$filter->isStatic()) {
                continue;
            }

            $data = $filter->get($key, true);
            if ($this->isExistParams($data)) {
                $collection->add($data);
            }
        }

        return CatalogProductFilterListData::fromCollection($collection);
    }

    private function isExistParams(CatalogProductFilterData $data): bool
    {
        return !(empty($data->settings->options?->toArray())
            && null === $data->settings->min
            && null === $data->settings->max);
    }
}
