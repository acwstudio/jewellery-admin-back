<?php

declare(strict_types=1);

namespace App\Modules\XmlFeed\Traits;

use App\Packages\DataObjects\Catalog\Product\ProductData;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\Catalog\ProductOffer\ProductOfferData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;

trait ProductTrait
{
    public function getPriceAmount(
        ProductOfferData $productOffer,
        OfferPriceTypeEnum $type
    ): int {
        /** @var ProductOfferPriceData|null $priceData */
        $priceData = $productOffer->prices
            ->where('type', '=', $type)
            ->first();

        return (int)$priceData?->transform()['price']->getAmount();
    }

    public function getCorrectPriceAmounts(ProductOfferData $productOffer): array
    {
        $regular = $this->getPriceAmount($productOffer, OfferPriceTypeEnum::REGULAR);
        $promo = $this->getPriceAmount($productOffer, OfferPriceTypeEnum::PROMO);
        $sale = $this->getPriceAmount($productOffer, OfferPriceTypeEnum::SALE);

        if ($sale > 0) {
            $promo = $sale;
        }

        if ($promo >= $regular) {
            $regular = $promo;
            $promo = 0;
        }

        return [
            OfferPriceTypeEnum::REGULAR->value => $regular,
            OfferPriceTypeEnum::PROMO->value => $promo
        ];
    }

    public function getProductPriceAmounts(ProductData $product): array
    {
        $default = [
            OfferPriceTypeEnum::REGULAR->value => 0,
            OfferPriceTypeEnum::PROMO->value => 0
        ];

        if ($product->trade_offers->toCollection()->isEmpty()) {
            return $default;
        }

        $productOffer = null;
        /** @var ProductOfferData $offer */
        foreach ($product->trade_offers as $offer) {
            $regular = $this->getPriceAmount($offer, OfferPriceTypeEnum::REGULAR);
            if ($regular > 0) {
                $productOffer = $offer;
                break;
            }
        }

        if (null === $productOffer) {
            return $default;
        }

        return $this->getCorrectPriceAmounts($productOffer);
    }
}
