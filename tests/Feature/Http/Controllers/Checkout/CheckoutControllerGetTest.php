<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Checkout;

use App\Modules\Catalog\Models\Product;
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

class CheckoutControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/checkout';
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
        Sanctum::actingAs($this->user);
    }

    public function testSuccessful()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);

        $productStockDataArray = $this->createProductStockDataCollection($products);
        $this->mockEnterprise1CApiClient($productStockDataArray);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('personal_data', $content);
        self::assertArrayHasKey('order_data', $content);
        self::assertArrayHasKey('saved_addresses', $content);
        self::assertArrayHasKey('saved_pvz', $content);
    }

    public function testFailureEmptyShopCart()
    {
        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyStock()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);

        $productStockDataArray = $this->createProductStockDataCollection($products, true);
        $this->mockEnterprise1CApiClient($productStockDataArray);

        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureEmptyProduct()
    {
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->createShopCart($products);

        $productStockDataArray = $this->createProductStockDataCollection($products, true);
        unset($productStockDataArray[0]);
        $this->mockEnterprise1CApiClient($productStockDataArray);

        $response = $this->get(self::METHOD);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function createShopCart(Collection $products): ShopCart
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $this->user]);

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            ShopCartItem::factory()->create([
                'shop_cart_id' => $shopCart->getKey(),
                'product_id' => $product->getKey(),
                'product_offer_id' => $offer->getKey(),
                'count' => 1
            ]);
        }

        return $shopCart;
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

    private function createProductStockDataCollection(Collection $products, bool $emptyCount = false): array
    {
        $data = [];

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            $data[] = new ProductStockData('123456', $product->sku, $offer->size, $emptyCount ? 0 : 10);
        }

        return $data;
    }
}
