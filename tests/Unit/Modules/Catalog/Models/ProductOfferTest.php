<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Catalog\Models;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;
use Tests\TestCase;

class ProductOfferTest extends TestCase
{
    public function testCurrentProductOfferPrice()
    {
        $productOffer = $this->createProductOffer();

        $price = $productOffer->currentProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::LIVE, $price->type);

        $this->removePrice($productOffer, OfferPriceTypeEnum::LIVE);
        $price = $productOffer->currentProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::SALE, $price->type);

        $this->removePrice($productOffer, OfferPriceTypeEnum::SALE);
        $price = $productOffer->currentProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::PROMO, $price->type);

        $this->removePrice($productOffer, OfferPriceTypeEnum::PROMO);
        $price = $productOffer->currentProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::REGULAR, $price->type);
    }

    public function testRegularProductOfferPrice()
    {
        $productOffer = $this->createProductOffer();

        $price = $productOffer->regularProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::LIVE, $price->type);

        $this->removePrice($productOffer, OfferPriceTypeEnum::LIVE);
        $price = $productOffer->regularProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::REGULAR, $price->type);
    }

    public function testPromoProductOfferPrice()
    {
        $productOffer = $this->createProductOffer();

        $price = $productOffer->promoProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::SALE, $price->type);

        $this->removePrice($productOffer, OfferPriceTypeEnum::SALE);
        $price = $productOffer->promoProductOfferPrice();
        self::assertInstanceOf(ProductOfferPrice::class, $price);
        self::assertEquals(OfferPriceTypeEnum::PROMO, $price->type);
    }

    public function testDiscountProductOfferPrice()
    {
        $productOffer = $this->createProductOffer();

        $priceAmount = $productOffer->discountProductOfferPrice();
        self::assertEquals(0, $priceAmount);

        $this->removePrice($productOffer, OfferPriceTypeEnum::LIVE);
        /* (25000 - 19999)/25000 * 100 = 20 */
        $priceAmount = $productOffer->discountProductOfferPrice();
        self::assertNotEquals(0, $priceAmount);
        self::assertEquals(20, $priceAmount);

        $this->removePrice($productOffer, OfferPriceTypeEnum::SALE);
        /* (25000 - 23000)/25000 * 100 = 8 */
        $priceAmount = $productOffer->discountProductOfferPrice();
        self::assertNotEquals(0, $priceAmount);
        self::assertEquals(8, $priceAmount);
    }

    private function createProductOffer(): ProductOffer
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::REGULAR,
            'price' => Money::RUB(25000 * 100)
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::PROMO,
            'price' => Money::RUB(23000 * 100)
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::LIVE,
            'price' => Money::RUB(24000 * 100)
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::SALE,
            'price' => Money::RUB(19999 * 100)
        ]);

        return $productOffer->refresh();
    }

    private function removePrice(ProductOffer $productOffer, OfferPriceTypeEnum $type): void
    {
        $productOffer->productOfferPrices()->getQuery()->where('type', '=', $type)->delete();
        $productOffer->refresh();
    }
}
