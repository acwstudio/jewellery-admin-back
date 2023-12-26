<?php

declare(strict_types=1);

namespace Tests\Unit\Packages\ModuleClients;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\AddShopCartItemData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\AddShopCartItemListData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\DeleteShopCartItemListData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\ModuleClients\ShopCartModuleClientInterface;
use Illuminate\Support\Facades\App;
use Laravel\Sanctum\Sanctum;
use Money\Money;
use Tests\TestCase;

class ShopCartModuleClientTest extends TestCase
{
    private ShopCartModuleClientInterface $shopCartModuleClient;

    protected function setUp(): void
    {
        parent::setUp();
        $this->shopCartModuleClient = App::make(ShopCartModuleClientInterface::class);
    }

    public function testSuccessfulGet(): void
    {
        $shopCartData = $this->shopCartModuleClient->getShopCart();

        self::assertInstanceOf(ShopCartData::class, $shopCartData);
    }

    public function testSuccessfulClear(): void
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $shopCartItem = ShopCartItem::factory()->create([
            'shop_cart_id' => ShopCart::factory()->create(['user_id' => $user->user_id])
        ]);

        $this->shopCartModuleClient->clearShopCart();

        self::assertModelMissing($shopCartItem);
    }

    public function testSuccessfulAddItem(): void
    {
        $productOffer = $this->createProductOffer();

        $addShopCartItems = [
            new AddShopCartItemData(
                $productOffer->product_id,
                $productOffer->id,
                1,
                true
            )
        ];

        $shopCartData = $this->shopCartModuleClient->addShopCartItems(
            new AddShopCartItemListData(
                AddShopCartItemData::collection($addShopCartItems)
            )
        );

        self::assertInstanceOf(ShopCartData::class, $shopCartData);
    }

    public function testSuccessfulDelete(): void
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        $productOffer = $this->createProductOffer();
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $user->user_id]);

        ShopCartItem::factory()->create([
            'shop_cart_id' => $shopCart,
            'product_offer_id' => $productOffer->id,
            'product_id' => $productOffer->product_id,
            'count' => 1
        ]);

        self::assertInstanceOf(ShopCart::class, $shopCart->refresh());
        self::assertCount(1, $shopCart->items()->getResults());

        $this->shopCartModuleClient->deleteShopCartItems(
            new DeleteShopCartItemListData(
                [$productOffer->id]
            )
        );

        self::assertInstanceOf(ShopCart::class, $shopCart->refresh());
        self::assertCount(0, $shopCart->items()->getResults());
    }

    private function createProductOffer(int $count = 10): ProductOffer
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
}
