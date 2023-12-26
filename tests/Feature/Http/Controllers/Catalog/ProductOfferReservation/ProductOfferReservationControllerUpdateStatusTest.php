<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOfferReservation;

use App\Modules\Catalog\Models\ProductOfferReservation;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Catalog\OfferReservationStatusEnum;
use App\Packages\Enums\Users\RoleEnum;
use App\Packages\Events\ProductOfferReservationStatusChanged;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductOfferReservationControllerUpdateStatusTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/reservation/{id}/status';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        Event::fake();

        /** @var ProductOfferReservation $productOfferReservation */
        $productOfferReservation = ProductOfferReservation::factory()->create([
            'status' => OfferReservationStatusEnum::PENDING
        ]);

        $method = self::setParamsInString(['id' => $productOfferReservation->getKey()], self::METHOD);
        $response = $this->put($method, [
            'status' => OfferReservationStatusEnum::CANCELED->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('status', $content);
        self::assertEquals(OfferReservationStatusEnum::CANCELED->value, $content['status']);

        Event::assertDispatched(
            function (ProductOfferReservationStatusChanged $event) use ($productOfferReservation) {
                return $event->getProductOfferId() === $productOfferReservation->product_offer_id;
            }
        );
    }

    public function testSuccessfulSame()
    {
        /** @var ProductOfferReservation $productOfferReservation */
        $productOfferReservation = ProductOfferReservation::factory()->create([
            'status' => OfferReservationStatusEnum::PENDING
        ]);

        $method = self::setParamsInString(['id' => $productOfferReservation->getKey()], self::METHOD);
        $response = $this->put($method, [
            'status' => OfferReservationStatusEnum::PENDING->value
        ]);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('status', $content);
        self::assertEquals(OfferReservationStatusEnum::PENDING->value, $content['status']);
    }

    public function testFailure()
    {
        $method = self::setParamsInString(['id' => 100500], self::METHOD);
        $response = $this->put($method, [
            'count' => 100500
        ]);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        /** @var ProductOfferReservation $productOfferReservation */
        $productOfferReservation = ProductOfferReservation::factory()->create([
            'status' => OfferReservationStatusEnum::PENDING
        ]);

        $method = self::setParamsInString(['id' => $productOfferReservation->getKey()], self::METHOD);
        $response = $this->put($method, [
            'status' => OfferReservationStatusEnum::CANCELED->value
        ]);
        $response->assertForbidden();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
