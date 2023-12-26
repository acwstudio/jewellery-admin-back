<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\ProductOffer;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use Tests\TestCase;

class ProductOfferControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/trade_offer/';

    public function testSuccessful()
    {
        $product = Product::factory()->create();
        $productOffers = ProductOffer::factory(3)->create(['product_id' => $product->getKey()]);

        foreach ($productOffers as $productOffer) {
            ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);
            ProductOfferPrice::factory()->create(['product_offer_id' => $productOffer->getKey()]);
        }

        $productOffer = $productOffers->random();

        $response = $this->get(self::METHOD . $productOffer->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayNotHasKey('error', $content);
        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('size', $content);
        self::assertArrayHasKey('count', $content);
        self::assertArrayHasKey('prices', $content);
        self::assertIsArray($content['prices']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
