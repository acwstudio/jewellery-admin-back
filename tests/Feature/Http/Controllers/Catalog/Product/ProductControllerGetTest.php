<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Catalog\Product;

use App\Modules\Catalog\Enums\FeatureTypeEnum;
use App\Modules\Catalog\Models\Feature;
use App\Modules\Catalog\Models\PreviewImage;
use App\Modules\Catalog\Models\Product;
use App\Modules\Catalog\Models\ProductFeature;
use App\Modules\Catalog\Models\ProductImageUrl;
use App\Modules\Catalog\Models\ProductOffer;
use App\Modules\Catalog\Models\ProductOfferPrice;
use App\Modules\Catalog\Models\ProductVideoUrl;
use App\Modules\Storage\Models\Media;
use App\Modules\Users\Models\WishlistProduct;
use App\Packages\Enums\Catalog\OfferPriceTypeEnum;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerGetTest extends TestCase
{
    private const METHOD = '/api/v1/catalog/product/';

    public function testSuccessful()
    {
        /** @var Product $product */
        $product = Product::factory()->create();

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('id', $content);
        self::assertArrayHasKey('slug', $content);
        self::assertArrayHasKey('categories', $content);
        self::assertArrayHasKey('sku', $content);
        self::assertArrayHasKey('name', $content);
        self::assertArrayHasKey('summary', $content);
        self::assertArrayHasKey('description', $content);
        self::assertArrayHasKey('manufacture_country', $content);
        self::assertArrayHasKey('rank', $content);
        self::assertArrayHasKey('preview_image', $content);
        self::assertArrayHasKey('images', $content);
        self::assertArrayHasKey('videos', $content);
        self::assertArrayHasKey('trade_offers', $content);
        self::assertArrayHasKey('product_features', $content);
        self::assertArrayHasKey('catalog_number', $content);
        self::assertArrayHasKey('supplier', $content);
        self::assertArrayHasKey('liquidity', $content);
        self::assertArrayHasKey('stamp', $content);
        self::assertArrayHasKey('meta_title', $content);
        self::assertArrayHasKey('meta_description', $content);
        self::assertArrayHasKey('meta_keywords', $content);
        self::assertArrayHasKey('is_active', $content);
        self::assertArrayHasKey('is_drop_shipping', $content);
        self::assertArrayHasKey('popularity', $content);
        self::assertArrayHasKey('on_wishlist', $content);
    }

    public function testSuccessfulByTradeOffers()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $productOffers = ProductOffer::factory(3)->create(['product_id' => $product->getKey()]);

        foreach ($productOffers as $productOffer) {
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $productOffer->getKey(),
                'type' => OfferPriceTypeEnum::REGULAR
            ]);
            ProductOfferPrice::factory()->create([
                'product_offer_id' => $productOffer->getKey(),
                'type' => OfferPriceTypeEnum::PROMO
            ]);
        }

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('trade_offers', $content);
        self::assertIsArray($content['trade_offers']);
        self::assertCount(3, $content['trade_offers']);
        foreach ($content['trade_offers'] as $offer) {
            self::assertArrayHasKey('id', $offer);
            self::assertArrayHasKey('prices', $offer);
            self::assertIsArray($offer['prices']);
            foreach ($offer['prices'] as $price) {
                self::assertArrayHasKey('id', $price);
                self::assertArrayHasKey('price', $price);
                self::assertArrayHasKey('type', $price);
            }
        }
    }

    public function testSuccessfulByProductFeature()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        ProductFeature::factory(3)->create(['product_id' => $product->getKey()]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('product_features', $content);
        self::assertIsArray($content['product_features']);
        self::assertCount(3, $content['product_features']);
        foreach ($content['product_features'] as $item) {
            self::assertArrayHasKey('uuid', $item);
            self::assertArrayHasKey('feature', $item);
            self::assertArrayHasKey('children', $item);
            self::assertArrayHasKey('value', $item);
            self::assertArrayHasKey('is_main', $item);
        }
    }

    public function testSuccessfulByProductFeatureChildren()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $parentProductFeature = ProductFeature::factory()->create(['product_id' => $product->getKey()]);
        ProductFeature::factory(3)->create([
            'product_id' => $product->getKey(),
            'parent_uuid' => $parentProductFeature->getKey()
        ]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('product_features', $content);
        self::assertIsArray($content['product_features']);
        self::assertCount(1, $content['product_features']);
        foreach ($content['product_features'] as $item) {
            self::assertArrayHasKey('uuid', $item);
            self::assertArrayHasKey('feature', $item);
            self::assertArrayHasKey('children', $item);
            self::assertArrayHasKey('value', $item);
            self::assertArrayHasKey('is_main', $item);

            self::assertCount(3, $item['children']);
            foreach ($item['children'] as $child) {
                self::assertArrayHasKey('uuid', $child);
                self::assertArrayHasKey('feature', $child);
                self::assertArrayHasKey('children', $child);
                self::assertArrayHasKey('value', $child);
                self::assertArrayHasKey('is_main', $child);
            }
        }
    }

    public function testSuccessfulByImageUrls()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        ProductImageUrl::factory()->create(['product_id' => $product->getKey(), 'is_main' => true]);
        ProductImageUrl::factory(2)->create(['product_id' => $product->getKey(), 'is_main' => false]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('preview_image', $content);
        self::assertIsArray($content['preview_image']);
        self::assertArrayHasKey('id', $content['preview_image']);
        self::assertEquals(-1, $content['preview_image']['id']);
        self::assertArrayHasKey('image_url_sm', $content['preview_image']);
        self::assertArrayHasKey('image_url_md', $content['preview_image']);
        self::assertArrayHasKey('image_url_lg', $content['preview_image']);

        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertCount(2, $content['images']);
        foreach ($content['images'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertEquals(-1, $item['id']);
            self::assertArrayHasKey('image_url_sm', $item);
            self::assertArrayHasKey('image_url_md', $item);
            self::assertArrayHasKey('image_url_lg', $item);
        }
    }

    public function testSuccessfulByVideoUrls()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        ProductVideoUrl::factory(2)->create(['product_id' => $product->getKey()]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('videos', $content);
        self::assertIsArray($content['videos']);
        self::assertCount(2, $content['videos']);
        foreach ($content['videos'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertArrayHasKey('src', $item);
        }
    }

    public function testSuccessfulByPreviewImageAndImageUrls()
    {
        $previewImage = $this->getPreviewImage();
        /** @var Product $product */
        $product = Product::factory()->create(['preview_image_id' => $previewImage]);

        $previewImage1 = $this->getPreviewImage();
        $previewImage2 = $this->getPreviewImage();

        $product->images()->sync([$previewImage1->getKey(), $previewImage2->getKey()]);
        $product->save();

        ProductImageUrl::factory()->create(['product_id' => $product->getKey(), 'is_main' => true]);
        ProductImageUrl::factory(2)->create(['product_id' => $product->getKey(), 'is_main' => false]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('preview_image', $content);
        self::assertIsArray($content['preview_image']);
        self::assertArrayHasKey('id', $content['preview_image']);
        self::assertNotEquals(-1, $content['preview_image']['id']);
        self::assertArrayHasKey('image_url_sm', $content['preview_image']);
        self::assertArrayHasKey('image_url_md', $content['preview_image']);
        self::assertArrayHasKey('image_url_lg', $content['preview_image']);

        self::assertArrayHasKey('images', $content);
        self::assertIsArray($content['images']);
        self::assertCount(2, $content['images']);
        foreach ($content['images'] as $item) {
            self::assertArrayHasKey('id', $item);
            self::assertNotEquals(-1, $item['id']);
            self::assertArrayHasKey('image_url_sm', $item);
            self::assertArrayHasKey('image_url_md', $item);
            self::assertArrayHasKey('image_url_lg', $item);
        }
    }

    public function testSuccessfulByWishlist()
    {
        $user = $this->getUser();
        Sanctum::actingAs($user);

        /** @var Product $product */
        $product = Product::factory()->create();

        WishlistProduct::factory()->create(['user_id' => $user, 'product_id' => $product]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('on_wishlist', $content);
        self::assertTrue($content['on_wishlist']);
    }

    public function testSuccessfulByProductFeatureInsert()
    {
        /** @var Product $product */
        $product = Product::factory()->create();
        $feature = Feature::factory()->create(['type' => FeatureTypeEnum::INSERT, 'value' => 'Корунд']);
        ProductFeature::factory()->create([
            'product_id' => $product,
            'feature_id' => $feature,
            'value' => 'Корунд синт.'
        ]);
        ProductFeature::factory()->create([
            'product_id' => $product,
            'feature_id' => $feature,
            'value' => 'Корунд сапфир'
        ]);

        $response = $this->get(self::METHOD . $product->slug);
        $response->assertSuccessful();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('product_features', $content);
        self::assertIsArray($content['product_features']);
        self::assertCount(2, $content['product_features']);
        foreach ($content['product_features'] as $item) {
            self::assertArrayHasKey('uuid', $item);
            self::assertArrayHasKey('feature', $item);
            self::assertArrayHasKey('children', $item);
            self::assertArrayHasKey('value', $item);
            self::assertArrayHasKey('is_main', $item);
        }
    }

    public function testFailure()
    {
        $response = $this->get(self::METHOD . 100500);
        $response->assertServerError();
        $content = json_decode($response->getContent(), true);

        self::assertArrayHasKey('error', $content);
    }

    private function getPreviewImage(): PreviewImage
    {
        /** @var PreviewImage $previewImage */
        $previewImage = PreviewImage::factory()->create();
        Media::factory()->create([
            'model_type' => PreviewImage::class,
            'model_id' => $previewImage->getKey()
        ]);

        return $previewImage;
    }
}
