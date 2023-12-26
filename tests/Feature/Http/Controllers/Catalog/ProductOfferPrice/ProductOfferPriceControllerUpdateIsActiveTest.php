<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOfferPrice;

use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductOfferPriceControllerUpdateIsActiveTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/{id}/price/{type}';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create(['is_active' => true]);

        $method = self::setParamsInString(
            [
                'id' => $productOfferPrice->product_offer_id,
                'type' => $productOfferPrice->type->value
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);

        if ($productOfferPrice->type->value == OfferPriceTypeEnum::REGULAR->value) {
            $response->assertServerError();
        } else {
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
    }

    public function testFailure()
    {
        $method = self::setParamsInString(
            [
                'id' => 100500,
                'type' => OfferPriceTypeEnum::REGULAR->value
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNotActive()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create(['is_active' => false]);

        $method = self::setParamsInString(
            [
                'id' => $productOfferPrice->product_offer_id,
                'type' => $productOfferPrice->type->value
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureNotType()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
                'type' => OfferPriceTypeEnum::PROMO,
                'is_active' => true
            ]);

        $method = self::setParamsInString(
            [
                'id' => $productOfferPrice->product_offer_id,
                'type' => OfferPriceTypeEnum::REGULAR->value
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureOtherType()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
            'type' => OfferPriceTypeEnum::PROMO,
            'is_active' => true
        ]);

        $method = self::setParamsInString(
            [
                'id' => $productOfferPrice->product_offer_id,
                'type' => OfferPriceTypeEnum::REGULAR->value
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureUnknownType()
    {
        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create([
            'type' => OfferPriceTypeEnum::PROMO,
            'is_active' => true
        ]);

        $method = self::setParamsInString(
            [
                'id' => $productOfferPrice->product_offer_id,
                'type' => 'unknown'
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        /** @var ProductOfferPrice $productOfferPrice */
        $productOfferPrice = ProductOfferPrice::factory()->create(['is_active' => true]);

        $method = self::setParamsInString(
            [
                'id' => $productOfferPrice->product_offer_id,
                'type' => $productOfferPrice->type->value
            ],
            self::METHOD
        );
        $response = $this->put($method, [
            'is_active' => false
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
