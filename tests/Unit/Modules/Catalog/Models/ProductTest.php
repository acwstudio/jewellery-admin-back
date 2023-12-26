<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Models;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function testCurrentProductOfferPrice()
    {
        $product = $this->createProduct();

        $array = $product->toSearchableArray();

        self::assertIsArray($array);
        self::assertArrayHasKey('price_min', $array);
        self::assertEquals(2300000, $array['price_min']);

        //3, 6, 10
        self::assertArrayHasKey('discount_max', $array);
        self::assertEquals(10, $array['discount_max']);
    }

    private function createProduct(): Product
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $productOffers = ProductOffer::factory(3)->create([
            'product_id' => $product
        ]);

        $amount = 26000;
        foreach ($productOffers as $key => $productOffer) {
            $k = $key + 1;
            $regularAmount = $amount - (100 * $k);
            $promoAmount = $amount - (1000 * $k);
            $this->createProductOfferPrice($productOffer, OfferPriceTypeEnum::REGULAR, $regularAmount);
            $this->createProductOfferPrice($productOffer, OfferPriceTypeEnum::SALE, $promoAmount);
        }

        return $product->refresh();
    }

    private function createProductOfferPrice(ProductOffer $productOffer, OfferPriceTypeEnum $type, int $amount): void
    {
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => $type,
            'price' => Money::RUB($amount * 100)
        ]);

        $productOffer->refresh();
    }
}
