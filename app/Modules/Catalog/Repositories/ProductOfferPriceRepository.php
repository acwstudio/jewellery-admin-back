<?php

declare(strict_types=1);

namespace App\Modules\Catalog\Repositories;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Support\Blueprints\ProductOfferPriceBlueprint;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Money\Money;

class ProductOfferPriceRepository
{
    public function create(ProductOfferPriceBlueprint $data, ProductOffer $productOffer): ProductOfferPrice
    {
        $this->checkCanCreate($productOffer, $data->type, $data->price);
        return $this->createPrice($productOffer, $data->type, $data->price);
    }

    public function updateIsActive(ProductOfferPrice $productOfferPrice, bool $isActive): bool
    {
        return $productOfferPrice->update([
            'is_active' => $isActive
        ]);
    }

    public function updateIsActiveByProductIds(
        array $productIds,
        OfferPriceTypeEnum $type,
        bool $isActive,
        ?bool $filterIsActive = null
    ): void {
        $query = ProductOfferPrice::query()
            ->whereHas(
                'productOffer',
                fn (Builder $builder) => $builder
                ->whereIn('product_id', $productIds)
            )
            ->where('type', '=', $type);

        if (null !== $filterIsActive) {
            $query->where('is_active', '=', $filterIsActive);
        };

        $query->update(['is_active' => $isActive]);
    }

    private function checkCanCreate(ProductOffer $productOffer, OfferPriceTypeEnum $type, Money $price): void
    {
        if (OfferPriceTypeEnum::REGULAR === $type) {
            return;
        }

        /** @var ProductOfferPrice|null $priceRegular */
        $priceRegular = $productOffer->productOfferPrices()->getQuery()
            ->where('is_active', '=', true)
            ->where('type', '=', OfferPriceTypeEnum::REGULAR)
            ->first();

        if (!$priceRegular instanceof ProductOfferPrice) {
            /** Если цена эфирная, то создаем регулярную цену */
            if (OfferPriceTypeEnum::LIVE === $type) {
                $this->createPrice($productOffer, OfferPriceTypeEnum::REGULAR, $price);
                return;
            }
            throw new \Exception('No regular price created');
        }

        /** Нельзя создать PROMO при условии PROMO >= REGULAR цены */
        if (OfferPriceTypeEnum::PROMO === $type && $price->greaterThanOrEqual($priceRegular->price)) {
            throw new \Exception('Promo price cannot be greater than or equal regular price');
        }
    }

    private function createPrice(ProductOffer $productOffer, OfferPriceTypeEnum $type, Money $price): ProductOfferPrice
    {
        $productOfferPrice = new ProductOfferPrice([
            'price' => $price,
            'type' => $type,
            'is_active' => true
        ]);

        $productOffer->productOfferPrices()->getQuery()
            ->where('type', '=', $type)
            ->update(['is_active' => false]);

        $productOfferPrice->productOffer()->associate($productOffer);
        $productOfferPrice->save();

        return $productOfferPrice;
    }
}
