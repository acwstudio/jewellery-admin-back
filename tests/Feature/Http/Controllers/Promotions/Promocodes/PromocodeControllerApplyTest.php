<?php

declare(strict_types=1);

namespace Http\Controllers\Promotions\Promocodes;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Live\Models\LiveProduct;
use App\Modules\Promotions\Enums\PromotionBenefitTypeEnum;
use App\Modules\Promotions\Enums\PromotionConditionTypeEnum;
use App\Modules\Promotions\Models\Promotion;
use App\Modules\Promotions\Models\PromotionBenefit;
use App\Modules\Promotions\Models\PromotionCondition;
use App\Modules\Promotions\Models\PromotionConditionRule;
use App\Modules\Promotions\Models\PromotionConditionRulePhone;
use App\Modules\ShopCart\Models\ShopCart;
use App\Modules\ShopCart\Models\ShopCartItem;
use App\Modules\ShopCart\UseCases\GetShopCart;
use App\Modules\Users\Models\User;
use App\Packages\DataObjects\Catalog\ProductOffer\Price\ProductOfferPriceData;
use App\Packages\DataObjects\ShopCart\ShopCartData;
use App\Packages\DataObjects\ShopCart\ShopCartItem\ShopCartItemData;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Users\RoleEnum;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use JsonException;
use Laravel\Sanctum\Sanctum;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Money\Money;
use Tests\TestCase;

class PromocodeControllerApplyTest extends TestCase
{
    private const METHOD = '/api/v1/promotions/promocode/apply';
    private User $user;
    private PhoneNumber $phoneNumber;

    /**
     * @throws NumberParseException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->getUser(RoleEnum::USER);
        $this->phoneNumber = PhoneNumberUtil::getInstance()->parse(
            '+71234567890',
            'RU',
            new \App\Packages\Support\PhoneNumber()
        );
        Sanctum::actingAs($this->user);
    }

    public function testPromocodeWithoutRule(): void
    {
        $this->createPromocode();
        $this->createShopCartWithProducts();

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertSuccessful();
    }

    public function testPromocodeWithRuleByRecipientSuccessful(): void
    {
        $this->createPromocode(true, true);
        $this->user->update([
            'phone' => $this->phoneNumber
        ]);
        $this->createShopCartWithProducts();

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertSuccessful();
    }

    public function testSuccessfulByIsFreeDelivery(): void
    {
        $this->createPromocode(benefitData: ['is_free_delivery' => true]);
        $this->createShopCartWithProducts();

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertSuccessful();
    }

    public function testSuccessfulByIsFreeDeliveryAndPercent(): void
    {
        $this->createPromocode(
            benefitData: ['is_free_delivery' => true, 'percent_amount' => 10]
        );
        $shopCart = $this->createShopCartWithProducts();

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertSuccessful();

        $shopCartData = App::call(GetShopCart::class, ['token' => $shopCart->token]);
        self::assertInstanceOf(ShopCartData::class, $shopCartData);
        $items = $shopCartData->items->all();
        self::assertNotEmpty($items);
        foreach ($items as $item) {
            self::assertInstanceOf(ShopCartItemData::class, $item);
            $price = $item->prices->where('type', '=', OfferPriceTypeEnum::PROMOCODE)->first();
            self::assertInstanceOf(ProductOfferPriceData::class, $price);
        }
    }

    public function testSuccessfulByNominalAmount(): void
    {
        $this->createPromocode(
            benefitData: ['nominal_amount' => Money::RUB(75000), 'percent_amount' => 0]
        );
        $products = Product::factory(1)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var ProductOffer $offer */
            $offer = $product->productOffers->first();
            /** @var ProductOfferPrice $price */
            $price = $offer->productOfferPrices->first();
            $price->update([
                'price' => Money::RUB(1000 * 100)
            ]);
            $product->refresh();
        }
        $shopCart = $this->createShopCartWithProducts($products);

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertSuccessful();

        $shopCartData = App::call(GetShopCart::class, ['token' => $shopCart->token]);
        self::assertInstanceOf(ShopCartData::class, $shopCartData);
        $items = $shopCartData->items->all();
        self::assertNotEmpty($items);
        foreach ($items as $item) {
            self::assertInstanceOf(ShopCartItemData::class, $item);
            $price = $item->prices->where('type', '=', OfferPriceTypeEnum::PROMOCODE)->first();
            self::assertInstanceOf(ProductOfferPriceData::class, $price);
            self::assertEquals('25000', $price->price->getAmount());
        }
    }

    public function testFailurePromocodeWithRuleByRecipientFailed(): void
    {
        $this->createPromocode(true, true);

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertServerError();
    }

    public function testFailed(): void
    {
        $response = $this->post(self::METHOD, ['promocode' => 'test']);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertIsArray($content['error']);
        self::assertArrayHasKey('error_data', $content['error']);
        self::assertEmpty($content['error']['error_data']);
    }

    public function testFailureBySale(): void
    {
        $this->createPromocode();
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->addProductOfferPrice($products, OfferPriceTypeEnum::SALE, 2000);
        $this->createShopCartWithProducts($products);

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertIsArray($content['error']);
        self::assertArrayHasKey('error_data', $content['error']);
        self::assertIsArray($content['error']['error_data']);
        self::assertArrayHasKey('products', $content['error']['error_data']);
        self::assertIsArray($content['error']['error_data']['products']);
        self::assertArrayHasKey('sale', $content['error']['error_data']['products']);
        self::assertArrayNotHasKey('live', $content['error']['error_data']['products']);
    }

    /**
     * @throws JsonException
     */
    public function testSuccessfulByLive(): void
    {
        $this->createPromocode();
        $products = Product::factory(3)->create(['setFull' => true]);
        $this->addProductOfferPrice($products, OfferPriceTypeEnum::LIVE, 2000);
        $this->addProductToLive($products);
        $this->createShopCartWithProducts($products);

        $response = $this->post(self::METHOD, ['promocode' => 'promocode']);
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertArrayHasKey('success', $content);
        self::assertTrue($content['success']);
    }

    private function createPromocode(
        bool $withRulePhone = false,
        bool $isSetPhoneNumber = false,
        array $benefitData = []
    ): void {
        $promotion = Promotion::factory()->create(['is_active' => true, 'description' => 'Промокод 10%']);

        $promotionCondition = PromotionCondition::factory()
            ->create(['promotion_id' => $promotion]);

        if ($withRulePhone) {
            $promotionConditionRule = PromotionConditionRule::factory()
                ->create([
                    'promotion_condition_id' => $promotionCondition,
                    'type' => PromotionConditionTypeEnum::BY_RECIPIENT
                ]);
            if ($isSetPhoneNumber) {
                PromotionConditionRulePhone::factory()
                    ->create([
                        'promotion_condition_rule_id' => $promotionConditionRule,
                        'phone' => $this->phoneNumber
                    ]);
            } else {
                PromotionConditionRulePhone::factory()
                    ->create([
                        'promotion_condition_rule_id' => $promotionConditionRule
                    ]);
            }
        }

        $data = [
            'promotion_id' => $promotion,
            'type' => PromotionBenefitTypeEnum::PROMOCODE,
            'promocode' => 'promocode'
        ];

        PromotionBenefit::factory()->create(array_merge($data, $benefitData));
    }

    private function createShopCartWithProducts(?Collection $products = null): ShopCart
    {
        if (null === $products) {
            $products = Product::factory(3)->create(['setFull' => true]);
        }

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

    private function addProductOfferPrice(Collection $products, OfferPriceTypeEnum $type, int $amount): void
    {
        /** @var Product $product */
        foreach ($products as $product) {
            /** @var ProductOffer $productOffer */
            foreach ($product->productOffers as $productOffer) {
                ProductOfferPrice::factory()->create([
                    'product_offer_id' => $productOffer,
                    'type' => $type,
                    'is_active' => true,
                    'price' => Money::RUB($amount * 100)
                ]);
            }
        }
    }

    private function addProductToLive(Collection $products): void
    {
        /** @var Product $product */
        foreach ($products as $product) {
            LiveProduct::factory()->create([
                'product_id' => $product->getKey(),
                'on_live' => true
            ]);
        }
    }
}
