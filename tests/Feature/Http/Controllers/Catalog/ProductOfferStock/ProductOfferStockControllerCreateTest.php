<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOfferStock;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Catalog\OfferStockReasonEnum;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductOfferStockControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/{id}/stock';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 10
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('count', $content);
        self::assertEquals(10, $content['count']);
    }

    public function testSuccessfulNewStock()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);

        $response = $this->post($method, [
            'count' => 10
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        $productOfferStocks = $productOffer->productOfferStocks()->get();
        self::assertCount(1, $productOfferStocks);

        /** @var ProductOfferStock $productOfferStockFirst */
        $productOfferStockFirst = $productOfferStocks->first();
        self::assertTrue($productOfferStockFirst->is_current);
        self::assertEquals(OfferStockReasonEnum::MANUAL, $productOfferStockFirst->reason);

        $response = $this->post($method, [
            'count' => 15,
            'type' => OfferPriceTypeEnum::REGULAR->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);

        $productOffer->refresh();
        $productOfferStocks = $productOffer->productOfferStocks()->get();
        self::assertCount(2, $productOfferStocks);

        /** @var ProductOfferStock $productOfferStock */
        foreach ($productOfferStocks as $productOfferStock) {
            if ($productOfferStock->getKey() === $productOfferStockFirst->getKey()) {
                self::assertFalse($productOfferStock->is_current);
            } else {
                self::assertTrue($productOfferStock->is_current);
            }

            self::assertEquals(OfferStockReasonEnum::MANUAL, $productOfferStock->reason);
        }
    }

    public function testFailure()
    {
        $method = self::setParamsInString(['id' => 100500], self::METHOD);
        $response = $this->post($method, [
            'count' => 100500
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNegative()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => -1
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureString()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 'count1'
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
            'count' => 10
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
