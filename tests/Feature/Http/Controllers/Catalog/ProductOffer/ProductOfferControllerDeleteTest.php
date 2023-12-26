<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOffer;

use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Users\Models\User;
use App\Packages\Enums\Users\RoleEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductOfferControllerDeleteTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/';
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->getUser(RoleEnum::ADMIN);
        Sanctum::actingAs($this->admin);
    }

    public function testSuccessful()
    {
        $productOffer = ProductOffer::factory()->create();
        ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);
        ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);

        $response = $this->delete(self::METHOD . $productOffer->getKey());
        $response->assertSuccessful();
    }

    public function testFailure()
    {
        $response = $this->delete(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    public function testFailureAccessDenied()
    {
        Sanctum::actingAs($this->getUser());

        $productOffer = ProductOffer::factory()->create();
        ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);
        ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);

        $response = $this->delete(self::METHOD . $productOffer->getKey());
        $response->assertForbidden();

        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
