<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Live\LiveProduct;

use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Live\Models\LiveProduct;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class LiveProductControllerGetListByOnLiveTest extends TestCase
{
    private const METHOD = '/api/v1/live/product/on_live';

    public function testSuccessful()
    {
        $products = $this->createProducts(20);

        $liveProducts = $products->random(5);
        $this->createLiveProducts($liveProducts);

        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertCount(5, $content['items']);
    }

    public function testSuccessfulEmpty()
    {
        $response = $this->get(self::METHOD);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('items', $content);
        self::assertEmpty($content['items']);
    }

    private function createProducts(int $count = 1): Collection
    {
        $products = Product::factory($count)->create(['setFull' => true]);
        /** @var Product $product */
        foreach ($products as $key => $product) {
            $product->update(['popularity' => $key + 1]);
            $offer = $product->productOffers->first();
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $offer->getKey(),
                'type' => OfferPriceTypeEnum::LIVE,
                'is_active' => true
            ]);
        }

        return $products;
    }

    private function createLiveProducts(Collection $products, bool $onLive = true): void
    {
        foreach ($products as $key => $product) {
            LiveProduct::factory()->create([
                'product_id' => $product,
                'number' => $key,
                'started_at' => Carbon::tomorrow()->addMinutes($key),
                'expired_at' => Carbon::now()->addDays(config('live.product.expire_days')),
                'on_live' => $onLive
            ]);
        }
    }
}
