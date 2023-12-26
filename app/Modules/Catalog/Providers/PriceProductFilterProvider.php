<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Providers;

use App\Modules\Catalog\Contracts\Providers\ProductFilterProviderContract;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterContextData;
use App\Packages\DataObjects\Catalog\Product\Filter\CatalogProductFilterData;
use App\Packages\DataObjects\Catalog\Product\Filter\GetListProductFilterData;
use App\Packages\Enums\FilterTypeEnum;
use Illuminate\Database\Eloquent\Builder;

class PriceProductFilterProvider implements ProductFilterProviderContract
{
    public const FILTER_NAME = 'price';

    public function get(
        int $position,
        bool $forStatic = false,
        ?GetListProductFilterData $data = null
    ): CatalogProductFilterData {
        return new CatalogProductFilterData(
            $position,
            'Цена',
            self::FILTER_NAME,
            FilterTypeEnum::NUM,
            $this->getContext()
        );
    }

    public function isStatic(): bool
    {
        return false;
    }

    private function getContext(): CatalogProductFilterContextData
    {
        $builder = ProductOfferPrice::query()
            ->whereHas(
                'productOffer',
                fn (Builder $productOfferBuilder) => $productOfferBuilder
                    ->whereHas(
                        'product',
                        fn (Builder $productBuilder) => $productBuilder
                            ->where('is_active', '=', true)
                    )
            )
            ->where('is_active', '=', true);

        $min = $builder->min('price') ?? 0;
        $max = $builder->max('price') ?? 0;

        return new CatalogProductFilterContextData(
            $this->convertDecimal($min),
            $this->convertDecimal($max)
        );
    }

    private function convertDecimal(int $value): int
    {
        if ($value === 0) {
            return $value;
        }

        return intval($value / 100);
    }
}
