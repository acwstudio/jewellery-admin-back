<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Checkout;

use App\Modules\Catalog\Models\Product;
use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Delivery\Models\Pvz;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\Users\Models\User;
use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductStockData;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class CheckoutControllerGetSummaryTest extends TestCase
{
    private User $user;

    /**
     * @throws \JsonException
     */
    public function testSuccessfulByPvz(): void
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);
        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);
        $pvz = Pvz::factory()->create();
        $data = [
            'delivery' => [
                'pvz_id' => $pvz->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('delivery', $content);
        self::assertNotEquals(0, $content['delivery']);
    }

    private function createShopCart(Collection $products): ShopCart
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $this->user])->first();
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            ShopCartItem::factory()->create([
                'shop_cart_id' => $shopCart->getKey(),
                'product_id' => $product->getKey(),
                'product_offer_id' => $offer->getKey(),
                'count' => 1,
            ]);
        }
        return $shopCart;
    }

    private function createProductStockDataCollection(Collection $products, bool $emptyCount = false): array
    {
        $data = [];
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            $data[] = new ProductStockData('1234565', $product->sku, $offer->size, $emptyCount ? 0 : 10);
        }
        return $data;
    }

    private function mockEnterprise1CApiClient(array $productStockDataArray): void
    {
        $this->mock(
            Enterprise1CApiClientContract::class,
            function (MockInterface $mock) use ($productStockDataArray) {
                $response = new ProductsGetStockResponseData(
                    true,
                    '',
                    ProductStockData::collection($productStockDataArray)
                );
                $mock->shouldReceive('productsGetStock')->andReturn($response);
            }
        );
    }

    /**
     * @throws \JsonException
     */
    public function testSuccessfulByCurrierDeliveryId(): void
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);
        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);
        $currierDeliveryAddress = CurrierDeliveryAddress::factory()->create(['user_id' => $this->user]);
        $currierDelivery = CurrierDelivery::factory()->create([
            'currier_delivery_address_id' => $currierDeliveryAddress,
        ]);
        $data = [
            'delivery' => [
                'currier_delivery_id' => $currierDelivery->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('delivery', $content);
        self::assertNotEquals(0, $content['delivery']);
    }

    public function testSuccessfulByIsFreeDeliveryPvz()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $shopCart = $this->createShopCart($products);
        $this->createPromocode($shopCart, ['is_free_delivery' => true]);
        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);
        $pvz = Pvz::factory()->create();
        $data = [
            'delivery' => [
                'pvz_id' => $pvz->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('delivery', $content);
        self::assertEquals(0, $content['delivery']);
    }

    private function createPromocode(ShopCart $shopCart, array $benefitData = []): void
    {
        $promotion = Promotion::factory()->create(['is_active' => true]);
        $data = [
            'promotion_id' => $promotion,
            'type'         => PromotionBenefitTypeEnum::PROMOCODE,
            'promocode'    => 'promocode',
        ];
        $benefit = PromotionBenefit::factory()->create(array_merge($data, $benefitData));
        PromocodeUsage::factory()->create([
            'promotion_benefit_id' => $benefit->getKey(),
            'shop_cart_token'      => $shopCart->token,
            'user_id'              => $this->user,
        ]);
    }

    public function testSuccessfulByNotIsFreeDeliveryPvz()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $shopCart = $this->createShopCart($products);
        $this->createPromocode($shopCart, ['is_free_delivery' => false]);
        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);
        $pvz = Pvz::factory()->create();
        $data = [
            'delivery' => [
                'pvz_id' => $pvz->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('delivery', $content);
        self::assertNotEquals(0, $content['delivery']);
    }

    public function testSuccessfulByIsFreeDeliveryCurrier()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $shopCart = $this->createShopCart($products);
        $this->createPromocode($shopCart, ['is_free_delivery' => true]);
        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);
        $currierDeliveryAddress = CurrierDeliveryAddress::factory()->create(['user_id' => $this->user]);
        $currierDelivery = CurrierDelivery::factory()->create([
            'currier_delivery_address_id' => $currierDeliveryAddress,
        ]);
        $data = [
            'delivery' => [
                'currier_delivery_id' => $currierDelivery->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('delivery', $content);
        self::assertEquals(0, $content['delivery']);
    }

    public function testSuccessfulByNotIsFreeDeliveryCurrier()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $shopCart = $this->createShopCart($products);
        $this->createPromocode($shopCart, ['is_free_delivery' => false]);
        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);
        $currierDeliveryAddress = CurrierDeliveryAddress::factory()->create(['user_id' => $this->user]);
        $currierDelivery = CurrierDelivery::factory()->create([
            'currier_delivery_address_id' => $currierDeliveryAddress,
        ]);
        $data = [
            'delivery' => [
                'currier_delivery_id' => $currierDelivery->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('delivery', $content);
        self::assertNotEquals(0, $content['delivery']);
    }

    public function testFailureEmptyShopCart()
    {
        $pvz = Pvz::factory()->create();
        $data = [
            'delivery' => [
                'pvz_id' => $pvz->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyStock(): void
    {
        $this->migrateFreshUsing();
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);
        $condition = true;
        $productStockDataArray = $this->createProductStockDataCollection(
            $products,
            emptyCount: true
        );
        /** @var ProductStockData $product */
        foreach ($productStockDataArray as $key => $product) {
            $newProduct = new ProductStockData(
                external_id: $product->external_id,
                sku: $product->sku,
                size: $product->size,
                stockCount: 0,
            );
            $productStockDataArray[$key] = $newProduct;
        }
        $pvz = Pvz::factory()->create();
        $data = [
            'delivery' => [
                'pvz_id' => $pvz->getKey(),
            ],
        ];
        $response = $this->get(self::METHOD . '?' . http_build_query($data));
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);
        self::assertArrayHasKey('error', $content);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
        Sanctum::actingAs($this->user);
    }
    private const METHOD = '/api/v1/checkout/summary';
    public const EMPTY_COUNT = true;
}
