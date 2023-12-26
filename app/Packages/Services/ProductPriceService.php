<?php

declare(strict_types=1);

namespace App\Packages\Services;

use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Exceptions\GetPriceException;
use Illuminate\Support\Collection;
use Money\Money;

class ProductPriceService
{
    /**
     * @param Collection<ProductOfferPriceData> $prices
     * @throws GetPriceException
     */
    public function getPrice(Collection $prices, array $exclude = []): Money
    {
        if ($prices->isEmpty()) {
            throw new GetPriceException();
        }

        $map = [];

        foreach ($prices as $price) {
            $map[$price->type->name] = $price->price;
        }

        $exclude = collect($exclude)->map(fn (OfferPriceTypeEnum $type) => $type->name);

        $types = config('catalog.product_offer_price_priority');
        $types = collect($types)->map(fn (OfferPriceTypeEnum $type) => $type->name);

        $types = collect($types)->diff($exclude);

        foreach ($types as $type) {
            if (array_key_exists($type, $map)) {
                return $map[$type];
            }
        }

        throw new GetPriceException();
    }

    public function getPriceByType(Collection $prices, OfferPriceTypeEnum $type): ?Money
    {
        /** @var ProductOfferPriceData $price */
        foreach ($prices as $price) {
            if ($price->type === $type) {
                return $price->price;
            }
        }

        return null;
    }
}
