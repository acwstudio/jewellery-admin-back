<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers\Feature;

use App\Modules\Catalog\Contracts\Providers\ProductFilterProviderContract;
use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterContextData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductOptionValueData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use App\Packages\Enums\FilterTypeEnum;
use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFeatureProductFilterProvider implements ProductFilterProviderContract
{
    public function get(
        int $position,
        bool $forStatic = false,
        ?GetListProductFilterData $data = null
    ): CatalogProductFilterData {
        return new CatalogProductFilterData(
            $position,
            $this->getFilterTitle(),
            $this->getFilterName(),
            $this->getFilterType(),
            $this->getContext()
        );
    }

    public function isStatic(): bool
    {
        return false;
    }

    public function getFilterName(): string
    {
        return "feature[{$this->getFeatureType()->value}]";
    }

    abstract public function getFilterTitle(): string;

    abstract public function getFeatureType(): FeatureTypeEnum;

    public function getFilterType(): FilterTypeEnum
    {
        return FilterTypeEnum::SELECT;
    }

    public function getFeatureValue(): string
    {
        return '';
    }

    private function getContext(): CatalogProductFilterContextData
    {
        if ($this->getFilterType() === FilterTypeEnum::NUM) {
            return $this->getNumContext();
        }

        return $this->getSelectContext();
    }

    private function getBuilder(): Builder
    {
        return Feature::query()->where('type', '=', $this->getFeatureType());
    }

    private function getSelectContext(): CatalogProductFilterContextData
    {
        $builder = $this->getBuilder();

        $options = [];

        /** @var \Illuminate\Support\Collection<Feature> $items */
        $items = $builder->orderByRaw('position ASC NULLS LAST')->get();

        /** @var Feature $item */
        foreach ($items as $item) {
            $count = $this->getCountFeature($item);

            $options[] = new CatalogProductOptionValueData(
                $item->value,
                (string) $item->id,
                $item->slug,
                $count
            );
        }

        /** @var \Spatie\LaravelData\DataCollection $collection */
        $collection = CatalogProductOptionValueData::collection($options);

        return new CatalogProductFilterContextData(options: $collection);
    }

    private function getNumContext(): CatalogProductFilterContextData
    {
        /** @var Feature|null $feature */
        $feature = $this->getBuilder()->where('value', '=', $this->getFeatureValue())->first();
        if (!$feature instanceof Feature) {
            return new CatalogProductFilterContextData();
        }

        $productFeature = $feature->productFeatures()->getQuery()
            ->selectRaw('MAX(value) AS max, MIN(value) AS min')->get()->first();

        $min = (int)$productFeature->min;
        $max = (int)$productFeature->max;

        return new CatalogProductFilterContextData($min, $max);
    }

    private function getCountFeature(Feature $feature): int
    {
        return $feature->productFeatures()
            ->getQuery()
            ->whereHas(
                'product',
                fn (Builder $productBuilder) => $productBuilder->where('is_active', '=', true)
            )
            ->count();
    }
}
