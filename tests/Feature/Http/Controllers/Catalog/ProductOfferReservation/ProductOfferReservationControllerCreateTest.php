<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOfferReservation;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferStock;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Exceptions\ModelNotCreatedException;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductOfferReservationControllerCreateTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/{id}/reservation';
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
        ProductOfferStock::factory()->create(['count' => 10, 'product_offer_id' => $productOffer->getKey()]);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 1
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('count', $content);
        self::assertEquals(1, $content['count']);
    }

    public function testSuccessfulNewReservation()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();
        ProductOfferStock::factory()->create(['count' => 10, 'product_offer_id' => $productOffer->getKey()]);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 2
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('count', $content);
        self::assertEquals(2, $content['count']);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->actingAs($this->admin)->post($method, [
            'count' => 3
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('count', $content);
        self::assertEquals(3, $content['count']);
    }

    public function testSuccessfulNewReservationLast()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();
        ProductOfferStock::factory()->create(['count' => 5, 'product_offer_id' => $productOffer->getKey()]);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 2
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('count', $content);
        self::assertEquals(2, $content['count']);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 3
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('count', $content);
        self::assertEquals(3, $content['count']);
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

    public function testFailureNotReservation()
    {
        /** @var ProductOffer $productOffer */
        $productOffer = ProductOffer::factory()->create();
        ProductOfferStock::factory()->create(['count' => 5, 'product_offer_id' => $productOffer->getKey()]);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);

        $response = $this->post($method, [
            'count' => 10
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
        self::assertArrayHasKey('code', $content['error']);
        self::assertEquals((new ModelNotCreatedException())->getCode(), $content['error']['code']);
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
        ProductOfferStock::factory()->create(['count' => 10, 'product_offer_id' => $productOffer->getKey()]);

        $method = self::setParamsInString(['id' => $productOffer->getKey()], self::METHOD);
        $response = $this->post($method, [
            'count' => 1
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
