<?php

declare(strict_types=1);

namespace App\Modules\Checkout\Services;

use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Checkout\ProductData;
use App\Packages\DataObjects\Promotions\Promocode\PromocodeData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Exceptions\Checkout\EmptyShopCardException;
use App\Packages\Facades\ProductPrice;
use App\Packages\ModuleClients\PromotionsModuleClientInterface;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Illuminate\Support\Collection;
use Money\Money;

class CheckoutService
{
    public function __construct(
        private readonly ShopCartModuleClientInterface $shopCartModuleClient,
        private readonly PromotionsModuleClientInterface $promotionsModuleClient
    ) {
    }

    /**
     * @throws EmptyShopCardException
     */
    public function getShopCartItems(): Collection
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();

        $items = collect($shopCart->items->toCollection());

        if ($items->count() === 0) {
            throw new EmptyShopCardException();
        }

        return $items;
    }

    public function getProducts(Collection $items): Collection
    {
        return $items->map(function (ShopCartItemData $shopCartItemData) {
            return new ProductData(
                $shopCartItemData->product_id,
                $shopCartItemData->preview_image,
                $shopCartItemData->slug
            );
        });
    }

    public function countProducts(Collection $items): int
    {
        return (int)$items->reduce(function (int $carry, ShopCartItemData $shopCartItemData) {
            return $carry + $shopCartItemData->count;
        }, 0);
    }

    public function calculateTotal(Collection $items): Money
    {
        return $items->reduce(function (Money $carry, ShopCartItemData $shopCartItemData) {
            $price = ProductPrice::getPrice(
                collect($shopCartItemData->prices->toCollection()),
                [
                    OfferPriceTypeEnum::SALE,
                    OfferPriceTypeEnum::PROMO,
                    OfferPriceTypeEnum::PROMOCODE
                ]
            );

            $count = $shopCartItemData->count;

            return $carry->add($price->multiply($count));
        }, Money::RUB(0));
    }

    public function calculateDiscount(Collection $items): Money
    {
        return $items->reduce(function (Money $carry, ShopCartItemData $shopCartItemData) {
            $prices = collect($shopCartItemData->prices->toCollection());

            if ($this->hasPriceType($prices, OfferPriceTypeEnum::PROMOCODE)) {
                $regularPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::PROMO);
                if (null === $regularPrice) {
                    $regularPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::REGULAR);
                }
                $promoPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::PROMOCODE);
            } else {
                if ($this->hasPriceType($prices, OfferPriceTypeEnum::LIVE)) {
                    return $carry;
                }

                $regularPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::REGULAR);
                $promoPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::SALE);
                if (null === $promoPrice) {
                    $promoPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::PROMO);
                }

                if ($promoPrice === null) {
                    return $carry;
                }
            }

            return $carry->add(($regularPrice->subtract($promoPrice))->multiply($shopCartItemData->count));
        }, Money::RUB(0));
    }

    /**
     * @param Collection<ShopCartItemData> $items
     */
    public function calculateFinalPrice(Collection $items): Money
    {
        return $items->reduce(function (Money $carry, ShopCartItemData $shopCartItemData) {
            $prices = collect($shopCartItemData->prices->toCollection());
            if ($this->hasPriceType($prices, OfferPriceTypeEnum::PROMOCODE)) {
                $promoPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::PROMOCODE);
            } else {
                if ($this->hasPriceType($prices, OfferPriceTypeEnum::LIVE)) {
                    $regularPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::LIVE);
                    return $carry->add($regularPrice?->multiply($shopCartItemData->count));
                }
                $promoPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::SALE);
                if (null === $promoPrice) {
                    $promoPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::PROMO);
                }

                if ($promoPrice === null) {
                    $regularPrice = ProductPrice::getPriceByType($prices, OfferPriceTypeEnum::REGULAR);
                    return $carry->add($regularPrice?->multiply($shopCartItemData->count));
                }
            }
            return $carry->add($promoPrice->multiply($shopCartItemData->count));
        }, Money::RUB(0));
    }

    /**
     * @param Collection<ShopCartItemData> $items
     */
    public function getSummary(Collection $items): Money
    {
        return $items->reduce(function (Money $summary, ShopCartItemData $shopCartItemData) {
            $price = ProductPrice::getPrice(collect($shopCartItemData->prices->toCollection()));
            return $summary->add($price->multiply($shopCartItemData->count));
        }, Money::RUB(0));
    }

    /**
     * @param Collection<ProductOfferPriceData> $prices
     * @param OfferPriceTypeEnum $type
     * @return bool
     */
    private function hasPriceType(Collection $prices, OfferPriceTypeEnum $type): bool
    {
        foreach ($prices as $price) {
            if ($price->type === $type) {
                return true;
            }
        }

        return false;
    }

    public function getPromocode(): ?PromocodeData
    {
        $shopCart = $this->shopCartModuleClient->getShopCart();
        return $this->promotionsModuleClient->getActivePromocode($shopCart->token);
    }

    public function getFinalPrice($items): Money
    {
        $price = $this->calculateFinalPrice($items);
        return Money::RUB(
            (int)floor(
                (float)$price->getAmount() / 100
            ) * 100
        );
    }
}
