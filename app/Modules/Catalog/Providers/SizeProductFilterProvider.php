<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers;

use App\Modules\Catalog\Contracts\Providers\ProductFilterProviderContract;
use App\Modules\Catalog\Models\ProductOffer;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterContextData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductOptionValueData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use App\Packages\Enums\FilterTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SizeProductFilterProvider implements ProductFilterProviderContract
{
    public const FILTER_NAME = 'size';

    public function get(
        int $position,
        bool $forStatic = false,
        ?GetListProductFilterData $data = null
    ): CatalogProductFilterData {
        $context = $forStatic ? $this->getContextByStatic() : $this->getContext($data);
        return new CatalogProductFilterData(
            $position,
            'Размер',
            self::FILTER_NAME,
            FilterTypeEnum::BUTTON,
            $context
        );
    }

    public function isStatic(): bool
    {
        return true;
    }

    private function getContext(): CatalogProductFilterContextData
    {
        $builder = ProductOffer::query()
            ->whereHas(
                'productOfferPrices',
                fn (Builder $productOfferPriceBuilder) => $productOfferPriceBuilder
                    ->where('is_active', '=', true)
            )
            ->whereHas(
                'productOfferStocks',
                fn (Builder $productOfferStockBuilder) => $productOfferStockBuilder
                    ->where('is_current', '=', true)
                    ->where('count', '>', 0)
            )
            ->getQuery()
            ->select(['catalog.product_offers.size', 'catalog.categories.slug'])
            ->join('catalog.products', 'catalog.product_offers.product_id', '=', 'catalog.products.id')
            ->join('catalog.product_categories', 'catalog.products.id', '=', 'catalog.product_categories.product_id')
            ->join('catalog.categories', 'catalog.product_categories.category_id', '=', 'catalog.categories.id')
            ->whereNotNull('size');

        return $this->getCatalogProductFilterContextData($builder->get());
    }

    private function getContextByStatic(): CatalogProductFilterContextData
    {
        $builder = ProductOffer::query()->getQuery()->whereNotNull('size');
        return $this->getCatalogProductFilterContextData($builder->get());
    }

    private function getCatalogProductFilterContextData(Collection $collection): CatalogProductFilterContextData
    {
        $sizes = $collection->groupBy('size')->map(function ($row) {
            return [
                'size' => $row->pluck('size')->first(),
                'categories' => $row->pluck('slug')->unique()->values(),
            ];
        });

        $sizes = Arr::sort(
            $sizes,
            function (array $size) {
                if (str_contains($size['size'], '-')) {
                    return stristr($size['size'], '-', true);
                }

                return $size['size'];
            }
        );

        $options = [];

        foreach ($sizes as $size) {
            $options[] = new CatalogProductOptionValueData(
                $size['size'],
                $size['size'],
                Str::slug("${size['size']}_razmera", '_', dictionary: ['.' => '_', ',' => '_']),
                null,
                ['categories' => $size['categories']->toArray()]
            );
        }

        /** @var \Spatie\LaravelData\DataCollection $collection */
        $collection = CatalogProductOptionValueData::collection($options);

        return new CatalogProductFilterContextData(options: $collection);
    }
}
