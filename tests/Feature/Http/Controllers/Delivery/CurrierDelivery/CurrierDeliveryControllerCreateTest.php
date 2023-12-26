<?php

declare(strict_types=1);

namespace Http\Controllers\Delivery\CurrierDelivery;

use App\Modules\Catalog\Models\Product;
use App\Modules\Delivery\Models\Carrier;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\Users\Models\User;
use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryCostData;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryGetCostResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Collection;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class CurrierDeliveryControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/delivery/currier';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
        Sanctum::actingAs($this->user);
        $this->mockEnterprise1CApiClient();
    }

    public function testSuccessful()
    {
        $currierDeliveryAddress = CurrierDeliveryAddress::factory()->create([
            'user_id' => $this->user
        ]);

        $data = [
            'delivery_address_id' => $currierDeliveryAddress->getKey(),
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('carrier_id', $content);
        self::assertArrayHasKey('price', $content);
        self::assertNotEquals(0, $content['price']);
        self::assertArrayHasKey('address', $content);
        self::assertArrayHasKey('currierDeliveryAddress', $content);
    }

    public function testSuccessfulByIsFreeDelivery()
    {
        $shopCart = $this->createShopCart();
        $this->createPromocode($shopCart, ['is_free_delivery' => true]);

        $currierDeliveryAddress = CurrierDeliveryAddress::factory()->create([
            'user_id' => $this->user
        ]);

        $data = [
            'delivery_address_id' => $currierDeliveryAddress->getKey(),
        ];

        $response = $this->post(self::METHOD, $data);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('carrier_id', $content);
        self::assertArrayHasKey('price', $content);
        self::assertEquals(0, $content['price']);
        self::assertArrayHasKey('address', $content);
        self::assertArrayHasKey('currierDeliveryAddress', $content);
    }

    public function testFailure()
    {
        $response = $this->post(self::METHOD, []);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function mockEnterprise1CApiClient(): void
    {
        $this->mock(Enterprise1CApiClientContract::class, function (MockInterface $mock) {
            $response = new DeliveryGetCostResponseData(
                true,
                '',
                DeliveryCostData::from([
                    'id' => '123',
                    'cost' => 2500
                ])
            );
            $mock->shouldReceive('deliveryGetCost')->andReturn($response);
        });
    }

    private function createShopCart(): ShopCart
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $this->user]);
        $products = Product::factory(3)->create(['setFull' => true]);

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

    private function createPromocode(ShopCart $shopCart, array $benefitData = []): void
    {
        $promotion = Promotion::factory()->create(['is_active' => true]);

        $data = [
            'promotion_id' => $promotion,
            'type' => PromotionBenefitTypeEnum::PROMOCODE,
            'promocode' => 'promocode'
        ];

        $benefit = PromotionBenefit::factory()->create(array_merge($data, $benefitData));

        PromocodeUsage::factory()->create([
            'promotion_benefit_id' => $benefit->getKey(),
            'shop_cart_token' => $shopCart->token,
            'user_id' => $this->user
        ]);
    }
}
