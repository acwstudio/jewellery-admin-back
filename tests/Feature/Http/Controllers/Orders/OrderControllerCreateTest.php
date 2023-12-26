<?php

declare(strict_types=1);

namespace Http\Controllers\Orders;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Delivery\Models\CurrierDelivery;
use App\Modules\Delivery\Models\CurrierDeliveryAddress;
use App\Modules\Payment\Enums\PaymentTypeEnum;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodePrice;
use App\Modules\Promotions\Modules\Promocodes\Models\PromocodeUsage;
use App\Modules\Promotions\Modules\Sales\Models\Sale;
use App\Modules\Promotions\Modules\Sales\Models\SaleProduct;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\Users\Models\User;
use App\Packages\ApiClients\Enterprise1C\Contracts\Enterprise1CApiClientContract;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductData;
use App\Packages\ApiClients\Enterprise1C\Request\ProductsGetCount\ProductsGetStockRequestData;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryCostData;
use App\Packages\ApiClients\Enterprise1C\Response\DeliveryGetCost\DeliveryGetCostResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductsGetStockResponseData;
use App\Packages\ApiClients\Enterprise1C\Response\ProductsGetCount\ProductStockData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Orders\DeliveryType;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Money\Money;
use Tests\TestCase;

class OrderControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/orders/order';

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser();
        Sanctum::actingAs($this->user);
        $this->mockAMQPModuleClient([]);
        $this->mockEnterprise1CApiClient();
    }

    public function testSuccessful()
    {
        $shopCart = $this->createShopCart(true);
        $promocode = $this->createPromocode($shopCart);
        $promocodeUsage = $this->createPromocodeUsage($shopCart, $promocode);

        self::assertEmpty($promocodeUsage->order_id);

        $response = $this->post(self::METHOD, $this->getData());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);

        $promocodeUsage->refresh();
        self::assertNotEmpty($promocodeUsage->order_id);
    }

    public function testSuccessfulByPromocode()
    {
        $shopCart = $this->createShopCart();
        $promocode = $this->createPromocode($shopCart, ['percent_amount' => 10]);
        $promocodeUsage = $this->createPromocodeUsage($shopCart, $promocode);

        $this->createPromotionPrices($shopCart, $promocode);

        self::assertEmpty($promocodeUsage->order_id);

        $response = $this->post(self::METHOD, $this->getData());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);

        $promocodeUsage->refresh();
        self::assertNotEmpty($promocodeUsage->order_id);
    }

    public function testSuccessfulBySale()
    {
        $shopCart = $this->createShopCart(true);
        $this->createSaleProducts($shopCart);
        $this->createPromocode($shopCart);

        $response = $this->post(self::METHOD, $this->getData());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
    }

    private function getData(?array $data = []): array
    {
        $currierDelivery = $this->createCurrierDelivery();
        $defaultData = [
            'delivery' => [
                'delivery_type' => DeliveryType::CURRIER->value,
                'currier_delivery_id' => $currierDelivery->getKey()
            ],
            'personal_data' => [
                'phone' => '+79087788999',
                'email' => $this->user->email ?? 'test@mail.ru',
                'name' => $this->user->name ?? 'Иван',
                'surname' => $this->user->surname ?? 'Иванов'
            ],
            'payment_type' => PaymentTypeEnum::CASH->value
        ];

        return array_merge_recursive($defaultData, $data);
    }

    private function createShopCart(bool $isSale = false): ShopCart
    {
        /** @var ShopCart $shopCart */
        $shopCart = ShopCart::factory()->create(['user_id' => $this->user]);
        $products = Product::factory(3)->create(['setFull' => true]);

        /** @var Product $product */
        foreach ($products as $product) {
            /** @var \App\Modules\Catalog\Models\ProductOffer $offer */
            $offer = $product->productOffers->first();
            if ($isSale) {
                $this->createProductOfferPriceSale($offer);
            }
            ShopCartItem::factory()->create([
                'shop_cart_id' => $shopCart->getKey(),
                'product_id' => $product->getKey(),
                'product_offer_id' => $offer->getKey(),
                'count' => 1
            ]);
        }

        return $shopCart;
    }

    private function createPromocode(ShopCart $shopCart, array $benefitData = []): PromotionBenefit
    {
        $promotion = Promotion::factory()->create(['is_active' => true]);

        $data = [
            'promotion_id' => $promotion,
            'type' => PromotionBenefitTypeEnum::PROMOCODE,
            'promocode' => 'promocode'
        ];

        /** @var PromotionBenefit $benefit */
        $benefit = PromotionBenefit::factory()->create(array_merge($data, $benefitData));
        return $benefit;
    }

    private function createPromocodeUsage(ShopCart $shopCart, PromotionBenefit $benefit): PromocodeUsage
    {
        /** @var PromocodeUsage $promocodeUsage */
        $promocodeUsage = PromocodeUsage::factory()->create([
            'promotion_benefit_id' => $benefit->getKey(),
            'shop_cart_token' => $shopCart->token,
            'user_id' => $this->user
        ]);
        return $promocodeUsage;
    }

    private function createCurrierDelivery(): CurrierDelivery
    {
        /** @var CurrierDelivery $currierDelivery */
        $currierDelivery = CurrierDelivery::factory()->create([
            'currier_delivery_address_id' => CurrierDeliveryAddress::factory()->create([
                'user_id' => $this->user,
            ]),
        ]);
        return $currierDelivery;
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

            /** @var \Mockery\Expectation $mockProductsGetStock */
            $mockProductsGetStock = $mock->shouldReceive('productsGetStock');
            $mockProductsGetStock->andReturnUsing(
                function (ProductsGetStockRequestData $requestData) {
                    $products = collect($requestData->products->all());

                    $items = $products->map(
                        fn (ProductData $productData) => ProductStockData::from([
                            'UID' => '123',
                            'VendorCode' => $productData->art,
                            'Size' => $productData->size,
                            'StockCount' => 20
                        ])
                    );

                    return new ProductsGetStockResponseData(
                        true,
                        '',
                        ProductStockData::collection($items)
                    );
                }
            );
        });
    }

    private function createProductOfferPriceSale(ProductOffer $productOffer): void
    {
        /** @var ProductOfferPrice $price */
        $price = $productOffer->productOfferPrices
            ->whereIn('type', [OfferPriceTypeEnum::REGULAR, OfferPriceTypeEnum::LIVE])
            ->first();
        $salePrice = (int)$price->price->getAmount() - 100000;
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $productOffer,
            'type' => OfferPriceTypeEnum::SALE,
            'price' => Money::RUB($salePrice)
        ]);
    }

    private function createPromotionPrices(ShopCart $shopCart, PromotionBenefit $benefit): void
    {
        /** @var ShopCartItem $shopCartItem */
        foreach ($shopCart->items as $shopCartItem) {
            PromocodePrice::factory()->create([
                'product_offer_id' => $shopCartItem->product_offer_id,
                'shop_cart_token' => $shopCart->token,
                'promotion_benefit_id' => $benefit->getKey(),
                'price' => Money::RUB(999 * 100)
            ]);
        }
    }

    private function createSaleProducts(ShopCart $shopCart, ?PromotionBenefit $benefit = null): void
    {
        if (null === $benefit) {
            /** @var PromotionBenefit $benefit */
            $benefit = PromotionBenefit::factory()->create([
                'type' => PromotionBenefitTypeEnum::SALE
            ]);
            PromotionCondition::factory()->create([
                'promotion_id' => $benefit->promotion,
                'start_at' => Carbon::now()->subDay(),
                'finish_at' => Carbon::now()->addDays(10)
            ]);
        }

        $sale = Sale::factory()->create([
            'promotion_id' => $benefit->promotion
        ]);

        /** @var ShopCartItem $shopCartItem */
        foreach ($shopCart->items as $shopCartItem) {
            SaleProduct::factory()->create([
                'sale_id' => $sale,
                'product_id' => $shopCartItem->product_id
            ]);
        }
    }
}
