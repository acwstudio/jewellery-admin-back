<?php

declare(strict_types=1);

namespace Http\Controllers\Checkout;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\Users\Models\User;
use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductStockData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Collection;
use JsonException;
use Laravel\Sanctum\Sanctum;
use Mockery\LegacyMockInterface;
use Mockery\Mock;
use Mockery\MockInterface;
use Money\Money;
use Tests\TestCase;

class CheckoutControllerCalculateTest extends TestCase
{
    private const METHOD = '/api/v1/checkout/calculate';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
        Sanctum::actingAs($this->user);
    }

    /**
     * @throws JsonException
     */
    public function testSuccessful(): void
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('products', $content);
        self::assertArrayHasKey('products_count', $content);
        self::assertArrayHasKey('products_total', $content);
        self::assertArrayHasKey('products_final_price', $content);
        self::assertArrayHasKey('discount', $content);
        self::assertArrayHasKey('promocode', $content);
    }

    public function testSuccessfulByPromocode(): void
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $shopCart = $this->createShopCart($products);
        $this->addPromocodePrices($products, $shopCart->token);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('discount', $content);
        self::assertNotEmpty($content['discount']);
    }

    /**
     * @throws JsonException
     */
    public function testFailureEmptyShopCart(): void
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('error', $content);
    }

    private function createShopCart(Collection $products): ShopCart
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $this->user]);

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            ShopCartItem::factory()->create([
                'shop_cart_id' => $shopCart->getKey(),
                'product_id' => $product->getKey(),
                'product_offer_id' => $offer->getKey(),
                'count' => 1
            ]);
        }

        return $shopCart->refresh();
    }

    private function addPromocodePrices(Collection $products, string $shopCartToken): void
    {
        $promocodeBenefit = PromotionBenefit::factory()->create();
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var ProductOffer $productOffer */
            $productOffer = $product->productOffers->first();
            /** @var ProductOfferPrice $price */
            $price = $productOffer->productOfferPrices
                ->where('type', '=', OfferPriceTypeEnum::REGULAR)
                ->where('is_active', '=', true)
                ->first();
            $newPriceAmount = (int)$price->price->getAmount() - (150 * 100);
            PromocodePrice::factory()->create([
                'shop_cart_token' => $shopCartToken,
                'product_offer_id' => $productOffer,
                'price' => Money::RUB($newPriceAmount),
                'promotion_benefit_id' => $promocodeBenefit
            ]);
        }
    }
}
