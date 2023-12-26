<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\ShopCart;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use Carbon\Carbon;
use Money\Money;
use Tests\TestCase;

abstract class ShopCartTestCase extends TestCase
{
    public function createProductOffer(int $count = 10): ProductOffer
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        ProductOfferStock::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'count' => $count,
            'reason' => OfferStockReasonEnum::MANUAL
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::REGULAR,
            'is_active' => true,
            'price' => Money::RUB(fake()->numberBetween(10000, 20000))
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::PROMO,
            'is_active' => true,
            'price' => Money::RUB(fake()->numberBetween(1000, 9999))
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer->getKey(),
            'type' => OfferPriceTypeEnum::EMPLOYEE,
            'is_active' => true,
            'price' => Money::RUB(fake()->numberBetween(10000, 20000))
        ]);

        return $productOffer;
    }

    public function addShopCartItem(ShopCart $shopCart, ProductOffer $productOffer, int $count = 1): ShopCartItem
    {
        /** @var ShopCartItem $shopCartItem */
        $shopCartItem = ShopCartItem::factory()->create([
            'shop_cart_id' => $shopCart->getKey(),
            'product_id' => $productOffer->product->id,
            'product_offer_id' => $productOffer->getKey(),
            'count' => $count,
            'created_at' => Carbon::now()->addSeconds(Carbon::now()->micro)
        ]);

        $shopCart->refresh();

        return $shopCartItem;
    }
}
