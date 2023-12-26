<?php

declare(strict_types=1);

namespace Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductVideoUrl;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Money\Money;
use Tests\TestCase;

class ProductControllerGetItemExtendedTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product_item_extended/';

    public function testSuccessful()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['setFull' => true]);

        $response = $this->get(self::METHOD . $product->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertArrayHasKey('sku', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('description', $content);
        self::assertArrayHasKey('categories', $content);
        self::assertArrayHasKey('image', $content);
        self::assertArrayHasKey('images', $content);
        self::assertArrayHasKey('videos', $content);
        self::assertArrayHasKey('offers', $content);
        self::assertArrayHasKey('price', $content);
        self::assertArrayHasKey('price_old', $content);
        self::assertArrayHasKey('on_wishlist', $content);
    }

    public function testSuccessfulBySalePrice()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['setFull' => true]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $product->productOffers->first(),
            'type' => OfferPriceTypeEnum::SALE,
            'price' => Money::RUB(100000)
        ]);
        $product->updateInScout();

        $response = $this->get(self::METHOD . $product->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('price_old', $content);
        self::assertNotEmpty($content['price_old']);
    }

    public function testSuccessfulByLivePrice()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['setFull' => true]);
        $product->categories()->attach(Category::factory()->create());

        ProductOfferPrice::factory()->create([
            'product_offer_id' => $product->productOffers->first(),
            'type' => OfferPriceTypeEnum::LIVE,
            'price' => Money::RUB(300000)
        ]);
        ProductOfferPrice::factory()->create([
            'product_offer_id' => $product->productOffers->first(),
            'type' => OfferPriceTypeEnum::SALE,
            'price' => Money::RUB(100000)
        ]);
        ProductOffer::factory()->create(['product_id' => $product]);
        $product->updateInScout();

        $response = $this->get(self::METHOD . $product->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('price', $content);
        self::assertIsArray($content['price']);
        self::assertArrayHasKey('amount', $content['price']);
        self::assertEquals(3000, $content['price']['amount']);
        self::assertArrayHasKey('price_old', $content);
        self::assertEmpty($content['price_old']);
    }

    public function testSuccessfulNotExistProductOfferPrices()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['setFull' => true]);
        ProductOffer::factory()->create(['product_id' => $product]);
        $product->updateInScout();

        $response = $this->get(self::METHOD . $product->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertEquals($product->getKey(), $content['id']);
        self::assertArrayHasKey('offers', $content);
        self::assertIsArray($content['offers']);
        self::assertCount(1, $content['offers']);
    }

    public function testSuccessfulByVideo()
    {
        /** @var Product $product */
        $product = Product::factory()->create(['setFull' => true]);
        ProductVideoUrl::factory()->create([
            'product_id' => $product
        ]);
        $product->updateInScout();

        $response = $this->get(self::METHOD . $product->getKey());
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('videos', $content);
        self::assertNotEmpty($content['videos']);
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }
}
