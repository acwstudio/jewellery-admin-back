<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Filters\Category;

use App\Modules\Catalog\Contracts\Filters\FilterProductQueryBuilderContract;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Database\Eloquent\Builder;

class CategoryQueryBuilderHasProductFilter implements FilterProductQueryBuilderContract
{
    public function apply(Builder $query, $value): Builder
    {
        $hasProduct = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if (!is_bool($hasProduct)) {
            throw new \Exception('The has_product parameter must be boolean.');
        }

        if ($hasProduct) {
            $this->filterHasProduct($query);
        } else {
            $this->filterNotHasProduct($query);
        }

        return $query;
    }

    private function filterHasProduct(Builder $query): void
    {
        $query
            ->whereHas(
                'products',
                fn (Builder $productCategories) => $productCategories
                    ->where('products.is_active', '=', true)
                    ->where(
                        fn (Builder $builder) => $builder
                            ->whereHas(
                                'imageUrls',
                                fn (Builder $imageUrlsBuilder) => $imageUrlsBuilder
                                    ->where('is_main', '=', true)
                            )
                            ->whereHas(
                                'productOffers.productOfferStocks',
                                fn (Builder $offerStockBuilder) => $offerStockBuilder
                                    ->where('is_current', '=', true)
                                    ->where('count', '>', 0)
                            )
                            ->whereHas(
                                'productOffers.productOfferPrices',
                                fn (Builder $offerPriceBuilder) => $offerPriceBuilder
                                    ->where('is_active', '=', true)
                                    ->where('type', '!=', OfferPriceTypeEnum::EMPLOYEE)
                                    ->where('price', '>', 0)
                            )
                            ->orWhereNotNull('products.preview_image_id')
                    )
            );
    }

    private function filterNotHasProduct(Builder $query): void
    {
        $query
            ->whereDoesntHave('products')
            ->orWhereHas(
                'products',
                fn (Builder $productBuilder) => $productBuilder
                    ->where('products.is_active', '=', false)
                    ->orWhereDoesntHave('imageUrls')
                    ->orWhereDoesntHave('productOffers.productOfferStocks')
                    ->orWhereHas(
                        'productOffers.productOfferStocks',
                        fn (Builder $stockBuilder) => $stockBuilder
                            ->where('is_current', '=', true)
                            ->where('count', '<=', 0)
                    )
                    ->orWhereDoesntHave('productOffers.productOfferPrices')
                    ->orWhereHas(
                        'productOffers.productOfferPrices',
                        fn (Builder $priceBuilder) => $priceBuilder
                            ->where('is_active', '=', true)
                            ->where('price', '<=', 0)
                    )
            );
    }
}
