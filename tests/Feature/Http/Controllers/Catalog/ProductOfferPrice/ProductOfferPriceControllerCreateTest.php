<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOfferPrice;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Money\Money;
use Tests\TestCase;

class ProductOfferPriceControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/{id}/price';

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs($this->getUser(RoleEnum::ADMIN));
    }

    public function testSuccessful()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => 100000,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('price', $content);
        self::assertIsArray($content['price']);
        self::assertArrayHasKey('amount', $content['price']);
        self::assertEquals(100000, $content['price']['amount']);
        self::assertArrayHasKey('currency', $content['price']);
    }

    public function testSuccessfulNewPrice()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);

        $response = $this->post($method, [
            'price' => 100000,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        $productOfferPrices = $productOffer->productOfferPrices()->get();
        self::assertCount(1, $productOfferPrices);

        /** @var ProductOfferPrice $productOfferPriceFirst */
        $productOfferPriceFirst = $productOfferPrices->first();
        self::assertTrue($productOfferPriceFirst->is_active);
        self::assertEquals(OfferPriceTypeEnum::REGULAR, $productOfferPriceFirst->type);

        $response = $this->post($method, [
            'price' => 15600,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        $productOffer->refresh();
        $productOfferPrices = $productOffer->productOfferPrices()->get();
        self::assertCount(2, $productOfferPrices);

        /** @var ProductOfferPrice $productOfferPrice */
        foreach ($productOfferPrices as $productOfferPrice) {
            if ($productOfferPrice->getKey() === $productOfferPriceFirst->getKey()) {
                self::assertFalse($productOfferPrice->is_active);
            } else {
                self::assertTrue($productOfferPrice->is_active);
            }

            self::assertEquals(OfferPriceTypeEnum::REGULAR, $productOfferPrice->type);
        }
    }

    public function testSuccessfulPromo()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
                'is_active' => true,
                'price' => Money::RUB(20000 * 100),
                'type' => OfferPriceTypeEnum::REGULAR
            ]);

        $method = self::setParamsInString(['id' => $productOfferPrice->product_offer_id], self::METHOD);
        $response = $this->post($method, [
            'price' => 10000,
            'type' => OfferPriceTypeEnum::PROMO->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertArrayHasKey('price', $content);
        self::assertIsArray($content['price']);
        self::assertArrayHasKey('amount', $content['price']);
        self::assertArrayHasKey('currency', $content['price']);
    }

    public function testSuccessfulLive()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
            'is_active' => true,
            'price' => Money::RUB(20000 * 100),
            'type' => OfferPriceTypeEnum::REGULAR
        ]);

        $method = self::setParamsInString(['id' => $productOfferPrice->product_offer_id], self::METHOD);
        $response = $this->post($method, [
            'price' => 10000,
            'type' => OfferPriceTypeEnum::LIVE->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertEquals(OfferPriceTypeEnum::LIVE->value, $content['type']);
        self::assertArrayHasKey('price', $content);
        self::assertIsArray($content['price']);
        self::assertArrayHasKey('amount', $content['price']);
        self::assertArrayHasKey('currency', $content['price']);
    }

    public function testSuccessfulLiveNoRegular()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();
        self::assertEmpty($productOffer->productOfferPrices);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => 10000,
            'type' => OfferPriceTypeEnum::LIVE->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('type', $content);
        self::assertEquals(OfferPriceTypeEnum::LIVE->value, $content['type']);
        self::assertArrayHasKey('price', $content);
        self::assertIsArray($content['price']);
        self::assertArrayHasKey('amount', $content['price']);
        self::assertArrayHasKey('currency', $content['price']);

        $productOffer->refresh();
        $types = [OfferPriceTypeEnum::REGULAR, OfferPriceTypeEnum::LIVE];
        self::assertNotEmpty($productOffer->productOfferPrices);
        self::assertCount(2, $productOffer->productOfferPrices);
        foreach ($productOffer->productOfferPrices as $price) {
            self::assertContainsEquals($price->type, $types);
        }
    }

    public function testFailure()
    {
        $method = self::setParamsInString(['id' => 100500], self::METHOD);
        $response = $this->post($method, [
            'price' => 100000,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailurePriceString()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => 'RUB1000',
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailurePriceNegative()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => -1000,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailurePriceArray()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => '[5]',
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailurePromoNotExistRegular()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => '1000',
            'type' => OfferPriceTypeEnum::PROMO->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailurePromoGreaterThenRegular()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
            'is_active' => true,
            'type' => OfferPriceTypeEnum::REGULAR
        ]);

        $method = self::setParamsInString(['id' => $productOfferPrice->product_offer_id], self::METHOD);
        $response = $this->post($method, [
            'price' => (int)$productOfferPrice->price->getAmount() + 1000,
            'type' => OfferPriceTypeEnum::PROMO->value
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'price' => 100000,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
